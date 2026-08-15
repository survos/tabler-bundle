### Favicon

`TablerBundle` serves a dynamic SVG favicon at `/favicon.svg` so apps get a useful,
recognizable icon without the usual favicon-generator/download/unzip workflow. It's
generated as one or two initials on a colored background, heavily cacheable, and
configurable per environment.

If your app already has a `public/favicon.svg` (or any static file at that path), the
web server serves that file directly and the dynamic route is never reached.

```yaml
# config/packages/tabler.yaml
tabler:
    favicon:
        enabled: true       # set false to fall back to your own static favicon
        text: OF            # 1-2 characters; defaults to initials derived from app.code
        background: '#364fc7'
        foreground: '#ffffff'
        shape: rounded       # square | rounded | circle
```

Override per environment to make the tab visually distinguish prod/wip/dev/test/demo:

```yaml
# config/packages/dev/tabler.yaml
tabler:
    favicon:
        background: '#fab005'
```
