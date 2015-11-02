@typedef {{}} steal.live-reload.options LiveReloadOptions
@parent steal.live-reload

The following options can be specified to configure [steal.live-reload]'s behavior, using any method specified in [System.config].

@option {Number} [liveReloadPort=8012]

Specifies a port to use to establish the WebSocket connection. By default `8012` will be used. This can be specified in the script tag or in your config.

@option {Boolean} [liveReload=true]

Specifies whether to try and connect with a WebSocket server. If provided as the string `false` (such as through the script tag), this is also honored.

This is only useful to temporarily disable live-reload while you have the server off.

@body
