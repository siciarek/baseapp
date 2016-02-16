Set up
------

Here is the sample documentation chapter.

Sphinx update
=============

.. code-block:: bash

    sudo apt-get install texlive-latex-base
    sudo apt-get install texlive-latex-recommended
    sudo apt-get install texlive-fonts-recommended
    sudo apt-get install texlive-latex-extra
    sudo apt-get install texlive-lang-polish
    sudo apt-get install dvipng

    sudo apt-get install xzdec
    tlmgr init-usertree
    tlmgr install titlesec
    tlmgr install framed
    tlmgr install threeparttable
    tlmgr install wrapfig
    tlmgr install upquote
    tlmgr install multirow

Code block
==========

.. code-block:: c

    #include <stdio.h>
    #include <stdlib.h>
    
    int main(int argc, char** argv) {
    
        printf("Hello, World!\n");    
        
        return EXIT_SUCCESS;
    }

Mathematical formula
====================

.. math::

    	\int_a^bu\frac{d^2v}{dx^2}\,dx
    	=\left.u\frac{dv}{dx}\right|_a^b
    	-\int_a^b\frac{du}{dx}\frac{dv}{dx}\,dx.

can be created with the following:

.. code-block:: rst

    .. math::
    
        \int_a^bu\frac{d^2v}{dx^2}\,dx
        =\left.u\frac{dv}{dx}\right|_a^b
        -\int_a^b\frac{du}{dx}\frac{dv}{dx}\,dx.

Set up Cloud9 environment
=========================
        
.. code-block:: bash

    $ sudo vim /etc/apache2/sites-enabled/001-cloud9.conf
    $ apachectl restart

Useful commands
===============

Apache commands
~~~~~~~~~~~~~~~

.. code-block:: bash

    $ apachectl start
    $ apachectl restart
    $ apachectl stop

MySQL Database console access

.. code-block:: bash

    $ mysql-ctl cli
    
PostreSQL Database console access

.. code-block:: bash

    $ sudo sudo -u postgres psql

.. code-block:: sql

    CREATE USER root SUPERUSER PASSWORD 'pass';
    

MySQL server commands

.. code-block:: bash

    $ mysql-ctl start

PostgreSQL server commands

.. code-block:: bash

    $ sudo service postgresql start

Deploy Symfony Application
==========================

    * http://symfony.com/doc/current/cookbook/deployment/tools.html


.. code-block:: bash

    #!/bin/bash

    HEIGHT=15
    WIDTH=40
    CHOICE_HEIGHT=4
    BACKTITLE="Backtitle here"
    TITLE="Title here"
    MENU="Choose one of the following options:"

    OPTIONS=(
    1 'Zażółć gęślą jaźń'
    2 'Option 2'
    3 'Option 3'
    )

    CHOICE=$(dialog --clear \
        --backtitle "$BACKTITLE" \
        --title "$TITLE" \
        --menu "$MENU" \
        $HEIGHT $WIDTH $CHOICE_HEIGHT \
        "${OPTIONS[@]}" \
        2>&1 >/dev/tty)

    clear

    case $CHOICE in
        1)
            echo "You chose Option 1"
            ;;
        2)
            echo "You chose Option 2"
            ;;
        3)
            echo "You chose Option 3"
            ;;
    esac




