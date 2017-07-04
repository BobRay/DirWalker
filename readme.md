DirWalker Extra for MODX Revolution
=======================================


**Author:** Bob Ray [Bob's Guides](https://bobsguides.com)

**Documentation:** [DirWalker Docs](https://bobsguides.com/dirwalker-tutorial.html)

**Bugs and requests:** [ConverDatabaseCharset Issues](https://github.com/BobRay/DirWalker/issues)

**Questions about using DirWalker** [MODX Forums](https://forums.modx.com)


By default, DirWalker creates an associative array of files by recursively walking through directories and (optionally) their descendants. By extending DirWalker and overriding its processFiles() method, you can process the files as they are found.

Directories can be excluded from the search. Files to include and exclude can be specified by strings or regex patterns in the filename.

See the files in the core/components/dirwalker/docs/examples directory for examples.

Installing DirWalker installs only the class file. Include it, and instantiate DirWalker as shown in the examples to use it.