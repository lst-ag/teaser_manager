.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: ../Includes.txt


.. _changelog:

ChangeLog
=========

Version 3.1.3 (2021-01-28)
##########################
- Add default value for `teaser-type` in tt_content table

Version 3.1.2 (2021-01-26)
##########################
* Bring back `'transOrigPointerField' => 'l10n_parent'`, needed in TYPO3 10!

Version 3.1.1 (2021-01-20)
##########################
* Make lazy loaded entities running correctly (see https://forge.typo3.org/issues/90215)

Version 3.1.0 (2021-01-07)
##########################

* Add API endpoint for getting all teasers or all teasers from one teaser-type as a JSON string
    * Add the `?type=1606461885` parameter to the url
    * Send the correct API secret in the Authorization header of every request. The API secret can be configured in the extension manager.
    * Use the API with HTTPS only! If you don't, the Authorization secret will be transmitted in plain text.
* Add possibility to return bodytext without html code inside: `$teaser->getPlainText`

Version 3.0.4 (2020-12-21)
##########################

* Remove deprecated `'transOrigPointerField' => 'l10n_parent'`
* Remove deprecated `'enableMultiSelectFilterTextfield' => true`

Version 3.0.3 (2020-11-25)
##########################

* Show palettes `dimension_settings` and `background_settings` if "lst-fluid-styled-content" is installed

Version 3.0.2 (2020-10-31)
##########################

* Fix default values for tt_content fields
* Apply coding conventions and style


Version 3.0.1 (2020-10-13)
##########################

* Change default values of tt_content fields
* Add missing labels in TCA
* Add missing default value for teaser column `hidden`

Version 3.0.0 (2020-08-12)
##########################

* [!!!] Change vendor prefix from `CHF` to `LST`
* Add support for TYPO3 10 and drop support for TYPO3 8
* Improve select options for person and persons

Version 2.0.2 (2020-01-14)
##########################

* Add new teaser field `style`

Version 2.0.1 (2019-12-18)
##########################

* Don't lazy load relations of teaser
* Adjust core-labels for TYPO3 9

Version 2.0.0 (2019-02-23)
##########################

* Support TYPO3 8 LTS and TYPO3 9 LTS
* Use doctrine for database requests
* Use inject methods instead of annotations
* Use `.typoscript` and `.tsconfig` file extensions
* Add backend module for teaser layouts
* !!! `lib.contentElement` is used instead of the old `lib.fluidContent`

.. important::

    You need to adjust the template paths for your custom templates.

* !!! TeaserLayout choice is stored in the wrong mm table.

.. important::

    When teaser layouts where used you need to manually reassign them.


Version 1.7.0 (2018-11-22)
##########################

* Add new teaser properties person and persons


Version 1.6.0 (2018-11-01)
##########################

* Add a plugin preview to the page module


Version 1.5.0 (2018-10-30)
##########################

* Display all tt_content core palettes


Version 1.4.0 (2018-08-29)
##########################

* Add new teaser property «size»


Version 1.3.0 (2018-07-19)
##########################

* Add teaser layouts for different representations of the same teaser type


Version 1.2.0 (2018-06-15)
##########################

* Add new teaser property «icon selector» for choosing an icon from a predefined list


Version 1.1.0 (2018-05-17)
##########################

* Add new property name to teaser for better identification in backend
* Add filter to teaser selector
* Reload teasers when teaser type changes


Version 1.0.0 (2017-11-28)
##########################

* Manage teasers in one place and use them wherever you want to
* Support TYPO3 7 LTS
