<?php
/**
 * DirWalker class file
 *
 * Copyright 2013 by Bob Ray <http://bobsguides.com>
 * Created on 01-25-2014
 *
 * DirWalker is free software; you can redistribute it and/or modify it under the
 * terms of the GNU General Public License as published by the Free Software
 * Foundation; either version 2 of the License, or (at your option) any later
 * version.
 *
 * DirWalker is distributed in the hope that it will be useful, but WITHOUT ANY
 * WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR
 * A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along with
 * DirWalker; if not, write to the Free Software Foundation, Inc., 59 Temple
 * Place, Suite 330, Boston, MA 02111-1307 USA
 *
 * @package dirwalker
 */


if (!class_exists('DirWalker')) {
    class DirWalker {
        /** @var $files array - array of files; created by dir_walk */
        protected $files = array();

        /** @var $props array - properties array */
        protected $props = array();

        /** @var array $includes - include files with these strings */
        protected $includes = array();

        /** @var bool $includesUseRegex - use a regex pattern for inclusion */
        protected $includesUseRegex = false;

        /** @var array $excludes - exclude files with these strings */
        protected $excludes = array();

        /** @var bool $excludesUseRegex - use a regex pattern for exclusion */
        protected $excludesUseRegex = false;

        /** @var array $excludeDirs - exclude directories with these strings */
        protected $excludeDirs = array();

        /**
         * If set, only filenames with these strings/patterns
         * will be includes in the list.
         *
         * @param array $includes - comma-separated list of strings or patterns
         * @param bool $useRegex - use a regex pattern for the search
         */
        public function setIncludes($includes = array(), $useRegex = false) {
            $this->includes = explode(',', $includes);
            $this->includesUseRegex = $useRegex;

        }

        /**
         * If set, filenames with these strings will be excluded
         * from the list.
         *
         * @param array $excludes - comma-separated list of strings or patterns
         * @param bool $useRegex - use a regex pattern for the search
         */
        public function setExcludes($excludes = array(), $useRegex = false) {
            $this->excludes = explode(',', $excludes);
            $this->excludesUseRegex = $useRegex;
        }

        /**
         * Directories containing these strings (and their descendants)
         * will be excluded from the search.
         *
         * @param $excludes - comma-separated list of strings
         */
        public function setExcludeDirs($excludes) {
            $this->excludeDirs = explode(',', $excludes);
        }

        /**
         * Test whether a filename matches an include pattern
         *
         * @param $file string - filename
         * @return bool
         */
        protected function hasIncludes($file) {
            $found = false;
            foreach ($this->includes as $string) {
                if ($this->includesUseRegex) {
                    if (preg_match($string, $file)) {
                        $found = true;
                    }
                } else {
                    if (strpos($file, $string) !== false) {
                        $found = true;
                    }
                }
            }

            return $found;
        }

        /**
         * Test whether a file matches an exclude pattern
         *
         * @param $file string - filename
         * @return bool
         */
        protected function hasExcludes($file) {
            $found = false;
            foreach ($this->excludes as $string) {
                if ($this->excludesUseRegex) {
                    if (preg_match($string, $file)) {
                        $found = true;
                    }
                } else {
                    if (strpos($file, $string) !== false) {
                        $found = true;
                    }
                }
            }

            return $found;
        }

        /**
         * Test whether a directory matches an exclude string
         *
         * @param $dir string - directory name
         * @return bool
         */
        protected function hasExcludeDirs($dir) {
            $found = false;
            foreach ($this->excludeDirs as $string) {
                if (strpos($dir, $string) !== false) {
                    $found = true;
                }
            }

            return $found;
        }


        /**
         * Recursively search directories for certain file types
         * Adapted from boen dot robot at gmail dot com's code on the scandir man page
         *
         * @param $dir - dir to search
         * @param bool $recursive - if false, only searches $dir, not it's descendants
         * @param string $baseDir - used internally -- do not send!
         */
        public function dirWalk($dir, $recursive = false, $baseDir = '') {
            /* remove trailing slash, if any, on the first pass */
            if (empty($baseDir)) {
                $dir = rtrim($dir, '/\\');
            }

            if ($dh = opendir($dir)) {
                /* Skip excluded directories, if any */
                if ((!empty($this->excludeDirs)) && ($this->hasExcludeDirs($dir))) {
                    closedir($dh);

                    return;
                }
                while (($file = readdir($dh)) !== false) {
                    if ($file === '.' || $file === '..') {
                        continue;
                    }
                    if (is_file($dir . '/' . $file)) {
                        /* Handle excludes, if any */
                        if ((!empty($this->excludes)) && $this->hasExcludes($file)) {
                            continue;
                        }
                        /* Handle includes, if any */
                        if ((!empty($this->includes)) && (!$this->hasIncludes($file))) {
                            continue;
                        }
                        /* Found one - add it to $this->files */
                        $this->processFile($dir, $file);

                    } elseif ($recursive && is_dir($dir . '/' . $file)) {
                        $this->dirWalk($dir . '/' . $file, $recursive, $baseDir . '/' . $file);
                    }
                }
                closedir($dh);
            }
        }


        /**
         * Default processor called by dirWalk() -- adds files
         * to $this->files Override in a derived class to do
         * something else, or to process files as they are found.
         *
         * @param $dir string - directory of file (no trailing slash)
         * @param $file string - filename of file
         */
        protected function processFile($dir, $file) {
            $this->files[$dir . '/' . $file] = $file;
        }

        /**
         * Empties $this->files prior to dirWalk()
         */
        public function resetFiles() {
            $this->files = array();
        }

        /**
         * Get associative array of files found by dirWalk()
         *
         * @return array
         */
        public function getFiles() {
            return $this->files;
        }
    }
}
