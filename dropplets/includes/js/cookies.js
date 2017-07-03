/*jslint */
/*global module: true, exports: true, define: false */

/**
 * jaaulde-cookies.js
 *
 * Copyright (c) 2005-2015, Jim Auldridge MIT License
 *
 */

/**
 *
 * @param {object} scope - a reference to the call scope
 * @param {undefined} undef - an undefined variable for comparison checks
 * @returns {void}
 */
(function (scope, undef) {
    'use strict';

    /**
     * IIFE for injecting cookies module into whichever require/scope is in use
     *
     * @param {string} name - the name of module being created
     * @param {function} definition - function which produces and returns the created module
     * @returns {void}
     */
    (function (name, definition) {
        if (typeof module === 'object' && module !== null && module.exports) {
            module.exports = exports = definition();
        } else if (typeof define === 'function' && define.amd) {
            define(definition);
        } else {
            scope[name] = definition();
        }
    }('cookies', function () {
            /* localize natives */
        var document = scope.document,
            /* opts and support */
            default_options = {
                expires: null,
                path: '/',
                domain: null,
                secure: false
            },
            /**
             *
             * @access private
             * @param {string} msg
             * @returns {void}
             */
            warn = function (msg) {
                if (typeof scope.console === 'object' && scope.console !== null && typeof scope.console.warn === 'function') {
                    warn = function (msg) {
                        scope.console.warn(msg);
                    };
                    warn(msg);
                }
            },
            /**
             *
             * @param {object} o
             * @returns {object}
             */
            resolveOptions = function (o) {
                var r,
                    e;

                if (typeof o !== 'object' || o === null) {
                    r = default_options;
                } else {
                    r = {
                        expires: default_options.expires,
                        path: default_options.path,
                        domain: default_options.domain,
                        secure: default_options.secure
                    };

                    /*
                     * I've been very finicky about the name and format of the expiration option over time,
                     * so I'm accounting for older styles to maintain backwards compatibility. Preferably it
                     * will be called "expires" and will be an instance of Date
                     */
                    if (typeof o.expires === 'object' && o.expires instanceof Date) {
                        r.expires = o.expires;
                    } else if (typeof o.expires_at === 'object' && o.expires_at instanceof Date) {
                        r.expires = o.expires_at;
                        warn('Cookie option "expires_at" has been deprecated. Rename to "expires". Support for "expires_at" will be removed in a version to come.');
                    } else if (typeof o.expiresAt === 'object' && o.expiresAt instanceof Date) {
                        r.expires = o.expiresAt;
                        warn('Cookie option "expiresAt" has been deprecated. Rename to "expires". Support for "expiresAt" will be removed in a version to come.');
                    } else if (typeof o.hoursToLive === 'number' && o.hoursToLive !== 0) {
                        e = new Date();
                        e.setTime(e.getTime() + (o.hoursToLive * 60 * 60 * 1000));
                        r.expires = e;
                        warn('Cookie option "hoursToLive" has been deprecated. Rename to "expires" and prodvide a Date instance (see documentation). Support for "hoursToLive" will be removed in a version to come.');
                    }

                    if (typeof o.path === 'string' && o.path !== '') {
                        r.path = o.path;
                    }

                    if (typeof o.domain === 'string' && o.domain !== '') {
                        r.domain = o.domain;
                    }

                    if (o.secure === true) {
                        r.secure = o.secure;
                    }
                }

                return r;
            },
            /**
             *
             * @access private
             * @param {object} o
             * @returns {string}
             */
            cookieOptions = function (o) {
                o = resolveOptions(o);

                return ([
                    (typeof o.expires === 'object' && o.expires instanceof Date ? '; expires=' + o.expires.toGMTString() : ''),
                    ('; path=' + o.path),
                    (typeof o.domain === 'string' ? '; domain=' + o.domain : ''),
                    (o.secure === true ? '; secure' : '')
                ].join(''));
            },
            /**
             *
             * @access private
             * @param {string} s
             * @returns {string}
             */
            trim = (function () {
                var trim_def;

                /* Some logic for `trim` and `isNaN` borrowed from http://jquery.com/ */
                if (String.prototype.trim) {
                    trim_def = function (s) {
                        return String.prototype.trim.call(s);
                    };
                } else {
                    trim_def = (function () {
                        var l,
                            r;

                        l = /^\s+/;
                        r = /\s+$/;

                        return function (s) {
                            return s.replace(l, '').replace(r, '');
                        };
                    }());
                }

                return trim_def;
            }()),
            /**
             *
             * @access private
             * @param {mixed} v
             * @returns {boolean}
             */
            isNaN = (function () {
                var p = /\d/,
                    native_isNaN = scope.isNaN;

                return function (v) {
                    return (v === null || !p.test(v) || native_isNaN(v));
                };
            }()),
            /**
             *
             * @access private
             * @returns {object}
             */
            parseCookies = (function () {
                var parseJSON,
                    p;

                if (JSON && typeof JSON.parse === 'function') {
                    parseJSON = function (s) {
                        var r = null;

                        if (typeof s === 'string' && s !== '') {
                            s = trim(s);

                            if (s !== '') {
                                try {
                                    r = JSON.parse(s);
                                } catch (e1) {
                                    r = null;
                                }
                            }
                        }

                        return r;
                    };
                } else {
                    parseJSON = function () {
                        return null;
                    };
                }

                p = new RegExp('^(?:\\{.*\\}|\\[.*\\])$');

                return function () {
                    var c = {},
                        s1 = document.cookie.split(';'),
                        q = s1.length,
                        i,
                        s2,
                        n,
                        v,
                        vv;

                    for (i = 0; i < q; i += 1) {
                        s2 = s1[i].split('=');

                        n = trim(s2.shift());
                        if (s2.length >= 1) {
                            v = s2.join('=');
                        } else {
                            v = '';
                        }

                        try {
                            vv = decodeURIComponent(v);
                        } catch (e2) {
                            vv = v;
                        }

                        /* Logic borrowed from http://jquery.com/ dataAttr method */
                        try {
                            vv = (vv === 'true')
                                ? true : (vv === 'false')
                                    ? false : !isNaN(vv)
                                        ? parseFloat(vv) : p.test(vv)
                                            ? parseJSON(vv) : vv;
                        } catch (ignore) {}

                        c[n] = vv;
                    }

                    return c;
                };
            }());

        return {
            /**
             * get - get one, several, or all cookies
             *
             * @access public
             * @static
             * @param {mixed} n {string} name of single cookie
             *                  {array} list of multiple cookie names
             *                  {void} if you want all cookies
             * @return {mixed} type/value of cookie as set
             *                 {null} if only one cookie is requested and is not found
             *                 {object} hash of multiple or all cookies (if multiple or all requested)
             */
            get: function (n) {
                var r,
                    i,
                    c = parseCookies();

                if (typeof n === 'string') {
                    r = (c[n] !== undef) ? c[n] : null;
                } else if (typeof n === 'object' && n !== null) {
                    r = {};

                    for (i in n) {
                        if (Object.prototype.hasOwnProperty.call(n, i)) {
                            if (c[n[i]] !== undef) {
                                r[n[i]] = c[n[i]];
                            } else {
                                r[n[i]] = null;
                            }
                        }
                    }
                } else {
                    r = c;
                }

                return r;
            },
            /**
             * filter - get array of cookies whose names match the provided RegExp
             *
             * @access public
             * @static
             * @param {RegExp} p The regular expression to match against cookie names
             * @return {object} hash of cookies whose names match the RegExp
             */
            filter: function (p) {
                var n,
                    r = {},
                    c = parseCookies();

                if (typeof p === 'string') {
                    p = new RegExp(p);
                }

                for (n in c) {
                    if (Object.prototype.hasOwnProperty.call(c, n) && n.match(p)) {
                        r[n] = c[n];
                    }
                }

                return r;
            },
            /**
             * set - set or delete a cookie with desired options
             *
             * @access public
             * @static
             * @param {string} n name of cookie to set
             * @param {mixed} v Any JS value. If not a string, will be JSON encoded (http://code.google.com/p/cookies/wiki/JSON)
             *                  {null} to delete
             * @param {object} o optional list of cookie options to specify
             * @return {void}
             */
            set: function (n, v, o) {
                if (typeof o !== 'object' || o === null) {
                    o = {};
                }

                if (v === undef || v === null) {
                    v = '';
                    o.expires = new Date();
                    o.expires.setFullYear(1978);
                } else {
                    /* Logic borrowed from http://jquery.com/ dataAttr method and reversed */
                    v = (v === true)
                        ? 'true' : (v === false)
                            ? 'false' : !isNaN(v)
                                ? String(v) : v;

                    if (typeof v !== 'string') {
                        if (typeof JSON === 'object' && JSON !== null && typeof JSON.stringify === 'function') {
                            v = JSON.stringify(v);
                        } else {
                            throw new Error('cookies.set() could not be serialize the value');
                        }
                    }
                }

                document.cookie = n + '=' + encodeURIComponent(v) + cookieOptions(o);
            },
            /**
             * del - delete a cookie (domain and path options must match those with which the cookie was set; this is really an alias for set() with parameters simplified for this use)
             *
             * @access public
             * @static
             * @param {mixed} n {string} name of cookie to delete
             *                  {boolean} true to delete all
             * @param {object} o optional list of cookie options to specify (path, domain)
             * @return {void}
             */
            del: function (n, o) {
                var d = {},
                    i;

                if (typeof o !== 'object' || o === null) {
                    o = {};
                }

                if (typeof n === 'boolean' && n === true) {
                    d = this.get();
                } else if (typeof n === 'string') {
                    d[n] = true;
                }

                for (i in d) {
                    if (Object.prototype.hasOwnProperty.call(d, i) && typeof i === 'string' && i !== '') {
                        this.set(i, null, o);
                    }
                }
            },
            /**
             * test - test whether the browser is accepting cookies
             *
             * @access public
             * @static
             * @return {boolean}
             */
            test: function () {
                var r = false,
                    n = 'test_cookies_jaaulde_js',
                    v = 'data';

                this.set(n, v);

                if (this.get(n) === v) {
                    this.del(n);
                    r = true;
                }

                return r;
            },
            /**
             * setOptions - set default options for calls to cookie methods
             *
             * @access public
             * @static
             * @param {object} o list of cookie options to specify
             * @return {void}
             */
            setOptions: function (o) {
                if (typeof o !== 'object') {
                    o = null;
                }

                default_options = resolveOptions(o);
            }
        };
    }));
}(this));