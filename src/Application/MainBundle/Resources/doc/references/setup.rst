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
    
    E = mc^2
