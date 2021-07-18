CKImageManager
==============

CKImageManager is an ajax/php image manager developed as a plugin for ckeditor.

It can do all the simple tasks a file manager can do:

* Handle files upload
* Delete files
* Rename files
* Create and delete folders

Instructions
------------

Clone the git repository in the same folder CKEditor is installed.

    git clone git://github.com/josercl/CKImageManager.git

The edit the CKImageManager/php/config.php file and edit

    $config["base_url"]="http://url/to/image/folder";
    $config["upload_path"]="/path/to/images/folder";

To the url and the folder where the images/flash animations will be uploaded

**NOTE**: The selected folder must have write permissions in order to upload to work.

After that is enough to just initialize CKEditor with the next options:

    $("textarea").ckeditor({
        filebrowserImageBrowseUrl : 'CKImageManager/CKImageManager.php?Type=Images',
        filebrowserFlashBrowseUrl : 'CKImageManager/CKImageManager.php?Type=Flash'   
    });

or you can add those options as default in the config.js file that comes with ckeditor just adding these lines:

    config.filebrowserImageBrowseUrl = 'CKImageManager/CKImageManager.php?Type=Images';
    config.filebrowserFlashBrowseUrl = 'CKImageManager/CKImageManager.php?Type=Flash';
