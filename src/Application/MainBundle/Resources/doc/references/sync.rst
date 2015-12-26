Synchronisation of a code with `rsync`
--------------------------------------

Here is the description of synchronizing local code with remote server using `rsync`.


Excluded files list
===================

You can place following list in the file /app/config/rsync_exclude.txt

..code-block:: bash

    *~
    *bak
    .git*
    /go
    app/data.db3
    app/bootstrap.php.cache
    app/cache
    app/logs
    app/config/parameters.yml
    app/config/rsync_exclude.txt
    features
    nbproject
    .idea
    php.ini
    bin
    temp
    behat.*
    src/Application/MainBundle/Tests
    src/Application/MainBundle/Resources/doc
    build.xml
    composer.json
    composer.lock
    composer.phar
    LICENSE
    properties.conf
    properties.conf.dist
    README.md
    sync
    go
    vendor
    web


Excecutable script
==================

You can place the script in main project directory eg. `./script`

..code-block:: bash

    #!/usr/bin/env bash

    PRIVATE_KEY=~/.ssh/maxie.id_rsa

    REMOTE_USER=maxie
    REMOTE_HOST=maxiehill.com
    REMOTE_PORT=22
    REMOTE_DIR=/home/maxie/workspace/service.maxiehill.com

    SOURCE=.
    TARGET=$REMOTE_USER@$REMOTE_HOST:$REMOTE_DIR

    MODE=--dry-run

    if [ "$1" = "--go" ]
    then
        MODE=
    fi

    clear

    rsync \
    $MODE \
    --rsh "ssh -p$REMOTE_PORT  -i $PRIVATE_KEY" \
    --itemize-changes \
    --verbose  \
    --human-readable \
    --times \
    --progress \
    --links \
    --stats \
    --compress \
    --recursive \
    --links \
    --delete \
    --exclude-from=app/config/rsync_exclude.txt \
    $SOURCE $TARGET
