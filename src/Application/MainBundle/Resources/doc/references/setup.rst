Set up
------

Here is the sample documentation chapter.

Sphinx update
=============

.. code-block:: bash

    sudo apt-get install xzdec
    sudo apt-get install texlive-latex-extra
    sudo apt-get install texlive-lang-polish
    sudo apt-get install dvipng
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
    $ sudo sudo -u postgres psql
    $ mysql-ctl cli
    $ apachectl --help

Deploy Symfony Application
==========================

    * http://symfony.com/doc/current/cookbook/deployment/tools.html