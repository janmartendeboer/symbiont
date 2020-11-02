# Introduction

Symbiont is a purpose-built language for expressive data manipulation. It is
named so, because it relies on a mutualistic symbiotic relationship with a
host language.

As an expressive scripting language, Symbiont is able to keep and update state
of data, as well as consume from and publish to external data sources.
The extent of these capabilities heavily rely on the host language and
implementation with that language.

By design, the language is intended to be decoupled from the host language. The
main host language is PHP. However, the syntax is purposely designed around
[Vaughan Pratt's Top Down Operator Precedence](https://dl.acm.org/citation.cfm?id=512931).
The implementation details are referenced from
Douglas Crockford's paper on a
[parser for Simplified JavaScript](http://crockford.com/javascript/tdop/)
that is written in Simplified Javascript.

## In the wild

Symbiont is used as an example language for the talk
[On top of the Elephpant](https://www.meetup.com/GroningenPHP/events/jhvhqrybcpbhb/).
*(Nov. 5th 2020)*

Feel free to join. No prior knowledge of programming language design is expected.

## Syntax example

A brief example of the syntax:

```javascript
$catalog: @products;
$product: $catalog.ensure('tnt');

$product
    .forEach($_.media_gallery_entries)
    .where($_.media_type = 'image')
    .keepIf($_.file matches pcre2:/portrait\.(jpg|png)$/);
```

The example consists of:

- Variable `$catalog` pointing to a storage named `products`.
- Variable `$product` pointing to an entity `tnt` from storage `products`.
- Variable `$_` pointing to the current node, within the selection.

The following operations are performed:

1. Variable `$catalog` points to storage `products`.
2. Variable `$product` points to storage `products` entry `tnt`.
   If entry `tnt` could not be found, it is created according to a predetermined
   structure, specific to the `products` storage.
3. For each child of the `media_gallery_entries` data node of `$product` having
   a media type `image`, a filter is applied.
   Only entries with a `file` that end in `portrait.jpg` or `portrait.png` are
   kept.
4. End of program clean-up is triggered. This ensures the modifications on
   `$product` are persisted to the `products` storage.

The previous example is written verbosely to highlight individual parts of the
syntax. The following code should work exactly the same:

```javascript
@products('tnt')
    .forEach($_.media_gallery_entries)
    .where($_.media_type = 'image')
    .keepIf($_.file matches pcre2:/portrait\.(jpg|png)$/);
```

# How to use Symbiont

Currently, Symbiont can be run on a local installation of PHP >= PHP 7.4, or
alternatively inside a Docker container.

## Environment variables

The following environment variables influence the execution of Symbiont.

| Variable           | Possible values              | Description |
|:-------------------|:----------------------------:|:------------|
| `SYMBIONT_VERBOSE` | Any non-zero length string.  | Shows the PHP stack trace on top of the Symbiont exception output. |
| `SYMBIONT_MODE`    | `tokenize`, `parse`, `graph` | Defaults to `parse`. Interprets the program up until the matching step and outputs the result. |

## Symbiont modes

For each `SYMBIONT_MODE`, the following is the result:

| Mode       | Output                                  | Combined with `SYMBIONT_VERBOSE` |
|:-----------|:----------------------------------------|:---------------------------------|
| `tokenize` | Tokens, line by line, with their value. | The file path, start of the token and end of the token are prefixed to each line. |
| `parse`    | AST as a JSON object.                   | No additional effects. |
| `graph`    | AST as a Graphviz digraph.              | No additional effects. |

## Local PHP installation

When PHP is installed locally, run your script as follows:

```
bin/symbiont /path/to/script.sym
```

Alternatively, one can start their script with a
[Shebang](https://en.wikipedia.org/wiki/Shebang_(Unix)) pointing to Symbiont.

```
#!/path/to/symbiont
```

Since Symbiont is programmed to ignore all comments and whitespace while parsing,
this does not interfere with the script.

## Docker container

> ★ Once Symbiont reaches a better stability, images will be pushed to Docker Hub.

To build a fresh Docker image running Symbiont, run:

```
docker build -t symbiont:latest .
```

Then, to run a local symbiont script:

```
docker run --rm -v $PWD:/app symbiont:latest examples/types/numbers.sym
```

## Development

To rebuild a fresh image after each changed line of code, would slow down
development.

While one can set up a prepared running container and mount the local project,
for ease of use, there is a `dev` script that uses an existing image with all
the platform requirements installed.

```
./dev tests/types/numbers.sym
```

### Watching Symbiont files

Within the project, a file watcher is configured for the `fileExtension` called
`Symbiont`. It uses the `dev` command to verify changes in `*.sym` files.

In order for the watcher to work, a file type `Symbiont` with file name pattern
`*.sym` has to be created.

> Follow
[this guide](https://www.jetbrains.com/help/phpstorm/creating-and-registering-file-types.html)
to create new file type associations within PhpStorm.

Once the watcher works, it checks if it can parse the current file on change. If
the parser works as expected, nothing happens. When an error occurs, the console
opens with the error output of the parser.

### Environment variable aliases

The `dev` script listens both to the documented environment variables, and a
version without the `SYMBIONT_` prefix. E.g.:

```
MODE=tokenize ./dev examples/types/numbers.sym
```

Has the same effect as:

```
SYMBIONT_MODE=tokenize ./dev examples/types/numbers.sym
```

If a pre-existing environment variable conflicts, use the version with prefix.
Prefixed versions supersede the non-prefixed versions.

E.g.:

```
MODE=tokenize SYMBIONT_MODE=parse ./dev examples/types/numbers.sym
```

In the above case, the used mode will be `parse`.

## Testing

To run unit tests against the library files, use the Symbiont configured
`phpunit.xml.dist` run configuration within PhpStorm.

> If Composer packages are out of date, first run the `composer update --dev`
run configuration within PhpStorm.
