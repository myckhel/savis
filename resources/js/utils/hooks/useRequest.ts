import { get } from 'lodash';
import { useCallback, useEffect, useLayoutEffect } from 'react';
import { batch } from 'react-redux';
import { useEventListener } from 'use-event-listeners';
import useState from 'use-react-state';
import { useMemoSelector, useReduxState, useSetState } from 'use-redux-states';

type SetDataOptions = {
  next?: boolean;
  data?: any;
  infinite?: boolean;
  dataName?: string;
  prependNextData?: boolean;
};

const _setData = (
  data: any,
  {
    next,
    data: _data = {},
    infinite = true,
    dataName,
    prependNextData
  }: SetDataOptions = {}
) =>
  next
    ? infinite
      ? {
          ...data,
          // @ts-expect-error
          [dataName]: prependNextData
            ? // @ts-expect-error
              [...data[dataName], ...(_data[dataName] || [])]
            : // @ts-expect-error
              [...(_data[dataName] || []), ...data[dataName]]
        }
      : data
    : data;

type UseBaseRequestProps = {
  setState: (state: any) => void;
  asyncRequest: (...args: any[]) => Promise<any>;
  params?: any[];
  state?: any;
  getState?: () => any;
  dep?: any[];
  loadOnMount?: boolean;
  infinite?: boolean;
  dataPoint?: string;
  dataName?: string;
  dataPath?: string;
  emits?: string[];
  listeners?: Record<string, Function>;
  removeListeners?: Record<string, Function>;
  eventParams?: Record<string, any>;
  setData?: typeof _setData;
  onSuccess?: (data: any) => void;
};

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
}: UseBaseRequestProps) => {
  const emitter = useEventListener(
    {
      // @ts-expect-error
      listeners,
      // @ts-expect-error
      removeListeners,
      params: { setState, getState, ...eventParams }
    },
    []
  );

  const setDataState = useSetState(dataPath as string);

  const request = useCallback(
    // @ts-expect-error
    async (...p) => {
      try {
        // @ts-expect-error
        if (getState()?.isLoading) return;

        setState({ isLoading: true });
        const data = await asyncRequest(...p);

        emits.map(event => emitter.emit(event, data));

        const resolvedData = get(data, dataPoint);

        onSuccess && onSuccess(resolvedData);

        batch(() => {
          dataPath &&
            // @ts-expect-error
            setDataState((s = {}) => {
              // @ts-expect-error
              resolvedData.map((data: any) => (s[data.id] = data));
              return s;
            });

          // @ts-expect-error
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
    // @ts-expect-error
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

const stateSelector = (s: any) => s || defaultRequestState;

type UseRequestProps = {
  name: string;
  asyncRequest: (...args: any[]) => Promise<any>;
  params?: any[];
  state?: any;
  resolver?: (state: any) => any;
  reducer?: (state: any, action: any) => any;
  [key: string]: any;
};

const useRequest = (
  {
    name,
    asyncRequest,
    params,
    state: reduxState = stateSelector,
    resolver,
    reducer,
    ...props
  }: UseRequestProps,
  dep: any[]
) => {
  const { setState, getState, selector, useStateSelector } = useReduxState({
    name,
    state: reduxState,
    // @ts-expect-error
    reducer
  });

  // @ts-expect-error
  const state = useStateSelector(resolver);

  const rest = useBaseRequest({
    // @ts-expect-error
    setState,
    asyncRequest,
    params,
    state,
    dep,
    getState,
    ...props
  });

  const next = useCallback(
    // @ts-expect-error
    (_params = {}, { prependNextData } = {}) => {
      const { data: { links: { next_page_url, limit } = {} } = {} } =
        getState();
      const canNext = !!next_page_url;

      // @ts-expect-error
      if (canNext || _params.page) {
        // @ts-expect-error
        const page = _params.page || parse_query_string(next_page_url).page;
        rest.request(
          { page, ...params?.[0], limit, ..._params },
          { next: true, prependNextData }
        );
      }
    },
    [params, getState]
  );

  // @ts-expect-error
  return { setState, selector, next, ...rest };
};

function parse_query_string(query: string) {
  var splits = query.split('?');
  const vars = splits[1].split('&');
  var query_string: Record<string, any> = {};
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

type UseDataRequestProps = {
  asyncRequest: (...args: any[]) => Promise<any>;
  path?: string;
  name: string;
  params?: any[];
  loadOnMount?: boolean;
};

export const useDataRequest = (
  { asyncRequest, path, name, params, loadOnMount }: UseDataRequestProps,
  dep: any[] = []
) => {
  const [{ isLoading }, setState] = useState({
    isLoading: false,
    isError: false,
    error: null
  });

  const data = useMemoSelector(path || name);
  const setData = useSetState(path || name);

  // @ts-expect-error
  const fetchData = useCallback(async (...p) => {
    try {
      setState({ isLoading: true, isError: false, error: null });
      const res = await asyncRequest(...p);
      // @ts-expect-error
      setData(res);
      setState({ isLoading: false });
    } catch (error) {
      // @ts-expect-error
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
