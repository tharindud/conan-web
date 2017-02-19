<?php
// Copyright 2017, Tharindu Dissanayake <tharindud@gmail.com>.
// Published under the MIT license.

// This file contains configuration for the application. Edit it as required.

// Path to the Conan executable. Leave this empty if the Conan executable is accessible
// via the PATH environment variable.
define("CONAN_PATH", "");

// Alias of the Conan remote repository to use. Leave this empty to use the local repository.
define("CONAN_REMOTE", "");

// URL of the Conan remote repository corresponding to the remote specified in CONAN_REMOTE.
// Leave this empty to allow the application to determine it by invoking Conan.
// Note that the remote repository URL is only used for display purposes and does not affect
// application behavior. It can be specified in the configuration instead of fetching
// it from Conan to keep Conan invocations to a minimum. 
define("CONAN_REMOTE_URL", "");

// Cache lifetime duration for Conan command outputs. Leave this at 0 to disable command output
// caching (meaning that Conan command will be executed always). If the same Conan command was
// executed before and is available in the cache (and has not expired), output from that execution
// would be used instead of executing a new command. Enabling caching makes the user interface
// more responsive but results shown would lag the Conan repository status.
define("CACHE_DURATION", 0);

// Path to the command result cache. Output from Conan commands would be cached here. Leave this blank
// to disable caching.
define("CACHE_PATH", "");
?>
