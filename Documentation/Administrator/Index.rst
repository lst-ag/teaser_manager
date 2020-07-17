.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: ../Includes.txt


.. _admin-manual:

Administrator Manual
====================

Target group: **Administrators**


.. _admin-installation:

Installation
------------

Install the extension via composer:

.. code-block:: text

   composer require lst/teaser-manager


.. _admin-setup:

Include static TypoScript
-------------------------

* Navigate to the template module
* Edit the whole template record
* Navigate to the tab «Includes»
* Select «Teaser Manager»


.. _admin-configuration:

Define global storage pid
-------------------------

* Create a new folder in the page tree and keep the uid of the folder in mind
* Navigate to the extension settings
* Set the `basic.globalStoragePid` to the uid of the folder
* Save the configuration
* Reload the backend

