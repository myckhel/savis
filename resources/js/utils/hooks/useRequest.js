import { get } from 'lodash';
import { useCallback, useEffect, useLayoutEffect } from 'react';
import { batch } from 'react-redux';
import { useEventListener } from 'use-event-listeners';
import { useMemoSelector, useReduxState, useSetState } from 'use-redux-states';
import useState from 'use-react-state';

const _setData = (
  data,
  { next, data: _data = {}, infinite = true, dataName, prependNextData } = {}
) =>
  next
    ? infinite
      ? {
          ...data,
          [dataName]: prependNextData
            ? [...data[dataName], ...(_data[dataName] || [])]
            : [...(_data[dataName] || []), ...data[dataName]]
        }
      : data
    : data;

const useBaseRequest = ({
  setState,
  asyncRequest,
  params = [],
  state,
  getState,
  dep = [],
  loadOnMount = true,
  infinite = true,
  dataPoint = 'data',
  dataName = 'data',
  dataPath,
  emits = [],
  listeners = {},
  removeListeners = {},
  eventParams = {},
  setData = _setData,
  onSuccess
}) => {
  const emitter = useEventListener(
    {
      listeners,
      removeListeners,
      params: { setState, getState, ...eventParams }
    },
    []
  );

  const setDataState = useSetState(dataPath);

  const request = useCallback(
    async (...p) => {
      try {
        if (getState()?.isLoading) return;

        setState({ isLoading: true });
        const data = await asyncRequest(...p);

        emits.map(event => emitter.emit(event, data));

        const resolvedData = get(data, dataPoint);

        onSuccess && onSuccess(resolvedData);

        batch(() => {
          dataPath &&
            setDataState((s = {}) => {
              resolvedData.map(data => (s[data.id] = data));
              return s;
            });

          setState(({ data: _data, ...s }) => ({
            ...s,
            isLoading: false,
            data: setData(get(data, dataPoint), {
              ...(p?.[1] || {}),
              data: _data,
              infinite,
              dataName
            })
          }));
        });
      } catch (e) {
        setState({ isLoading: false });
        console.log({ e });
      }
    },
    [
      asyncRequest,
      emits,
      infinite,
      dataPoint,
      dataName,
      getState,
      setDataState,
      dataPath
    ]
  );

  const refresh = useCallback(
    _params => request(...(_params || params)),
    [params]
  );

  useLayoutEffect(() => {
    if (loadOnMount) {
      request(...params);
    }
  }, [loadOnMount, state?.timestamp, ...dep]);

  return { setState, state, request, emitter, refresh, getState };
};

const defaultRequestState = {
  timestamp: new Date().getTime(),
  data: { page: 1, total: 0, limit: 10 }
};

const stateSelector = s => s || defaultRequestState;

const useRequest = (
  {
    name,
    asyncRequest,
    params,
    state: reduxState = stateSelector,
    resolver,
    reducer,
    ...props
  },
  dep
) => {
  const { setState, getState, selector, useStateSelector } = useReduxState({
    name,
    state: reduxState,
    reducer
  });

  const state = useStateSelector(resolver);

  const rest = useBaseRequest({
    setState,
    asyncRequest,
    params,
    state,
    dep,
    getState,
    ...props
  });

  const next = useCallback(
    (_params = {}, { prependNextData } = {}) => {
      const { data: { links: { next_page_url, limit } = {} } = {} } =
        getState();
      const canNext = !!next_page_url;

      if (canNext || _params.page) {
        const page =
          _params.page || parseInt(parse_query_string(next_page_url).page, 10);
        rest.request(
          { page, ...params?.[0], limit, ..._params },
          { next: true, prependNextData }
        );
      }
    },
    [params, getState]
  );

  return { setState, selector, next, ...rest };
};

function parse_query_string(query) {
  var splits = query.split('?');
  const vars = splits[1].split('&');
  var query_string = {};
  for (var i = 0; i < vars.length; i++) {
    var pair = vars[i].split('=');
    var key = decodeURIComponent(pair[0]);
    var value = decodeURIComponent(pair[1]);
    // If first entry with this name
    if (typeof query_string[key] === 'undefined') {
      query_string[key] = decodeURIComponent(value);
      // If second entry with this name
    } else if (typeof query_string[key] === 'string') {
      var arr = [query_string[key], decodeURIComponent(value)];
      query_string[key] = arr;
      // If third or later entry with this name
    } else {
      query_string[key].push(decodeURIComponent(value));
    }
  }
  return query_string;
}

export const useDataRequest = (
  { asyncRequest, path, name, params, loadOnMount },
  dep = []
) => {
  const [{ isLoading }, setState] = useState({
    isLoading: false,
    isError: false,
    error: null
  });

  const data = useMemoSelector(path || name);
  const setData = useSetState(path || name);

  const fetchData = useCallback(async (...p) => {
    try {
      setState({ isLoading: true, isError: false, error: null });
      const res = await asyncRequest(...p);
      setData(res);
      setState({ isLoading: false });
    } catch (error) {
      setState({ isLoading: false, error, isError: true });
      console.log(error);
    }
  }, []);

  useEffect(() => {
    loadOnMount && fetchData(params);
  }, [fetchData, loadOnMount, ...dep]);

  return { data, isLoading, setState };
};

export default useRequest;
