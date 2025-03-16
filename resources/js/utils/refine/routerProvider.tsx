import { router, usePage } from '@inertiajs/react';
import React, { useCallback } from 'react';
import qs from 'qs';
import { GoConfig, ParseResponse, RouterProvider } from '@refinedev/core';
import { Link } from '@inertiajs/react';

const routerProvider: RouterProvider = {
  go: () => {
    const { url } = usePage();

    const fn = useCallback(
      ({
        to,
        type,
        query,
        hash,
        options: { keepQuery, keepHash } = {}
      }: GoConfig) => {
        const currentUrl = new URL(url, window.location.origin);

        /** Construct query params */
        const urlQuery = {
          ...(keepQuery
            ? qs.parse(currentUrl.search, { ignoreQueryPrefix: true })
            : {}),
          ...query
        };

        if (urlQuery.to) {
          urlQuery.to = encodeURIComponent(`${urlQuery.to}`);
        }

        const hasUrlQuery = Object.keys(urlQuery).length > 0;
        const urlHash = `#${(hash || (keepHash ? currentUrl.hash : '')).replace(
          /^#/,
          ''
        )}`;
        const hasUrlHash = urlHash.length > 1;

        const fullPath = `${to || ''}${
          hasUrlQuery ? `?${qs.stringify(urlQuery)}` : ''
        }${hasUrlHash ? urlHash : ''}`;

        if (type === 'path') {
          return fullPath;
        }

        console.log({ router });

        /** Navigate using Inertia */
        router.visit(fullPath, {
          replace: type === 'replace'
        });
      },
      [url]
    );

    return fn;
  },

  back: () => {
    return useCallback(() => {
      window.history.back();
    }, []);
  },

  parse: () => {
    const { url } = usePage();

    const fn = useCallback(() => {
      const parsedUrl = new URL(url, window.location.origin);
      const params = Object.fromEntries(parsedUrl.searchParams.entries());

      const response: ParseResponse = {
        pathname: parsedUrl.pathname,
        params
      };

      return response;
    }, [url]);

    return fn;
  },

  Link: React.forwardRef<
    HTMLAnchorElement,
    { to: string; [prop: string]: any }
  >(function RefineLink(props, ref) {
    console.log(props);

    return <Link href={props.to} ref={ref} />;
  })
};

export default routerProvider;
