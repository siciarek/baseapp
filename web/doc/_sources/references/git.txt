Advanced GIT usage
------------------

Show git repo history
=====================

rich

.. code-block:: bash

    $ git log

brief

.. code-block:: bash

    $ git log --pretty=oneline


Revert changes in a single file
===============================

.. code-block:: bash

    $ git reset HEAD /your/path/to/the/project/Sender.php
    $ git checkout --  /your/path/to/the/project/Sender.php

Git tags support
================

Let us assume that our release has name "v1.0.0"

Create tag
~~~~~~~~~~

.. code-block:: bash

    $ git tag -a v1.0.0 -m "The very first release of the Application."

List tags
~~~~~~~~~

.. code-block:: bash

    $ git tag

Remove tag
~~~~~~~~~~

local

.. code-block:: bash

    $ git tag -d v1.0.1

remote

.. code-block:: bash

    $ git push origin :refs/tags/v1.0.1




Git branches support
====================

Clone master
~~~~~~~~~~~~

.. code-block:: bash

    $ git clone git@git.enovatis.pl:ino
    $ git clone git@git.enovatis.pl:ino.git
    $ git clone git@git.enovatis.pl:ino ino
    $ git clone git@git.enovatis.pl:ino.git ino
    $ git clone -b master git@git.enovatis.pl:ino
    $ git clone -b master git@git.enovatis.pl:ino.git
    $ git clone -b master git@git.enovatis.pl:ino ino
    $ git clone -b master git@git.enovatis.pl:ino.git ino

Clone branch
~~~~~~~~~~~~

.. code-block:: bash

    $ git clone -b devel git@git.enovatis.pl:ino

Let us assume that branch is named "devel".

Create branch
~~~~~~~~~~~~~

Run single command

.. code-block:: bash

    $ git checkout -b devel

or two

.. code-block:: bash

    $ git branch devel
    $ git checkout devel

then send it to remote server

.. code-block:: bash

    $ git push --set-upstream origin devel

Delete branch
~~~~~~~~~~~~~

local

.. code-block:: bash

    $ git branch -d devel

remote

.. code-block:: bash

    $ git push origin --delete devel

Show branches
~~~~~~~~~~~~~

.. code-block:: bash

    $ git branch

Switch to specific branch
~~~~~~~~~~~~~~~~~~~~~~~~~

.. code-block:: bash

    $ git checkout devel
    $ git checkout master


Show diff of previous version
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

.. code-block:: bash

    $ git diff HEAD@{1}


Show diff beetween branches
~~~~~~~~~~~~~~~~~~~~~~~~~~~

Implicit diff

.. code-block:: bash

    $ git diff master..devel

Name and status

.. code-block:: bash

    $ git diff --name-status master..devel

More info

.. code-block:: bash

    $ git diff --stat  master..devel


Merge branch
~~~~~~~~~~~~

local

.. code-block:: bash

    $ git checkout master
    $ git merge devel

remote

.. code-block:: bash

    $ git checkout -b master
    $ git checkout master
    $ git fetch origin
    $ git merge origin/devel

Undo merge branch
~~~~~~~~~~~~~~~~~

.. code-block:: bash

    $ git reset --hard origin/master


Patch branch
~~~~~~~~~~~~

.. code-block:: bash

    $ git diff --no-prefix master..devel > diff.patch
    $ patch < diff.patch

Access to branches
~~~~~~~~~~~~~~~~~~

    https://www.kernel.org/pub/software/scm/git/docs/howto/update-hook-example.txt
