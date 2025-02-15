# Debugging

Deptrac provides a series of debug commands that help you identify issues in
your depfile. All commands output one issue per line and can therefore be easily
combined with other tools like `wc` or `grep`.

## `debug:layer`

With the `debug:layer`-command you can list all tokens which are matched in
a specific layer. This command only shows tokens that would be emitted by your analyser configuration.

```bash
php deptrac.phar debug:layer --config-file=examples/DirectoryLayer.depfile.yaml Layer1

examples\Layer1\AnotherClassLikeAController
examples\Layer1\SomeClass
examples\Layer1\SomeClass2
```

## `debug:token`

The `debug:token` (previously `debug:class-like`)-command will let you know which layers a specified token belongs to. Since you can specify the token type, this commands ignores your analyser configuration for emitted token types.

```bash
php deptrac.phar debug:token --config-file=examples/DirectoryLayer.depfile.yaml 'examples\Layer1\AnotherClassLikeAController' class-like

Controller
Layer1
```

## `debug:unassigned`

With the `debug:unassigned`-command you list all tokens in your path that are
not assigned to any layer. This is useful to test that your collector
configuration for layers is correct.  This command only shows tokens that would be emitted by your analyser configuration.

```bash
php deptrac.phar debug:unassigned --config-file=examples/DirectoryLayer.depfile.yaml

examples\Layer1\AnotherClassLikeAController
examples\Layer1\SomeClass
examples\Layer1\SomeClass2
```
