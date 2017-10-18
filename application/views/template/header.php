<!doctype html>
<html class="no-js" lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>Durham Surnames Online</title>
        <link rel="stylesheet" href="<?= base_url(); ?>css/foundation.min.css">
        <link rel="stylesheet" href="<?= base_url(); ?>fonts/foundation-icons.css">
        <link rel="stylesheet" href="<?= base_url(); ?>css/app.css">
        <script src="https://code.jquery.com/jquery-2.1.4.min.js"></script>
    </head>
    <body>
        <div class="top-bar">
            <div class="top-bar-left">
                <ul class="menu">
                    <li class="menu-text">
                        <a href="https://www.durhamrecordsonline.com/index.php">
                            Back to Durham Records On-line
                        </a>
                    </li>
                    <li><a href="<?= base_url(); ?>search">Surname Search</a></li>
                    <li><a href="<?= base_url(); ?>about">About The Project</a></li>
                    <?php if ($this->ion_auth->is_admin()) { ?>
                        <li><a href="<?= base_url(); ?>admin">Admin Surname List</a></li>
                        <li><a href="<?= base_url(); ?>admin/ward_and_parish">Admin Ward &amp; Parish</a></li>
                        <li><a href="<?= base_url(); ?>bulk">Bulk Upload</a></li>
                    <?php } ?>
                </ul>
            </div>
        </div>

        <div class="callout large primary">
            <div class="row column text-center">
                <h1>Durham Surnames Online</h1>
                <h4 class="subheader">An analysis of the historic distribution and incidence of Durham surnames in the sixteenth century.</h4>
            </div>
        </div>
        <div class="row small-12 medium-8 large-7 columns">