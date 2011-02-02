<?php

require_once('PEAR/PackageFileManager2.php');

PEAR::setErrorHandling(PEAR_ERROR_DIE);

$packagexml = new PEAR_PackageFileManager2;

$packagexml->setOptions(array(
    'baseinstalldir'    => '/',
    'simpleoutput'      => true,
    'packagedirectory'  => './',
    'filelistgenerator' => 'file',
    'ignore'            => array('phpunit-bootstrap.php', 'phpunit.xml', 'generatePackage.php', 'tests/results/'),
    'dir_roles' => array(
        'tests'    => 'test',
    ),
    'exceptions' => array(
        'INSTALL' => 'doc',
        'README' => 'doc',
        'LICENSE' => 'doc',
    ),
));

$packagexml->setPackage('Zend_Cache_Backend_Mock');
$packagexml->setSummary('Proper Mock backend for use with testing Zend_Cache');
$packagexml->setDescription(
    'All items are stored in the backend instance (memory).  Allows for proper mocking.'
);

$packagexml->setChannel('empower.github.com/pirum');
$packagexml->setAPIVersion('0.1.0');
$packagexml->setReleaseVersion('0.1.0');

$packagexml->setReleaseStability('alpha');

$packagexml->setAPIStability('alpha');

$packagexml->setNotes('
* Initial release
');
$packagexml->setPackageType('php');
$packagexml->addRelease();

$packagexml->detectDependencies();

$packagexml->addMaintainer('lead',
                           'shupp',
                           'Bill Shupp',
                           'bshupp@empowercampaigns.com');
$packagexml->addMaintainer('lead',
                           'dcopeland',
                           'Dan Copeland',
                           'dcopeland@empowercampaigns.com');
$packagexml->setLicense('New BSD License',
                        'http://www.opensource.org/licenses/bsd-license.php');

$packagexml->setPhpDep('5.0.0');
$packagexml->setPearinstallerDep('1.4.0b1');
$packagexml->addPackageDepWithChannel('required', 'Zend', 'zend.googlecode.com/svn', '1.11.0');

$packagexml->generateContents();
$packagexml->writePackageFile();

?>
