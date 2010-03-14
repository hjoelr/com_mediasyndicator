<?php       

defined('_JEXEC') or die();

class JTableMediaSyndicatorFiles extends JTable
{
        var $id = 0;
        var $filename_large = '';
        var $filename_small = '';
        var $media_type_id = 1;
        var $title = '';
        var $artist = '';
        var $album = null;
        var $track = null;
        var $genre = null;
        var $comment = null;
        var $performed_date = '0000-00-00 00:00:00';
        var $venue = null;
        var $duration = null;
        var $it_block = 0;
        var $it_explicit = 0;
        var $it_category_id = 24;
        var $it_keywords = null;
        var $it_subtitle = null;
        var $checked_out = 0;
        var $checked_out_time = '0000-00-00 00:00:00';
        var $published = 0;
        
        function __construct(&$db)
        {
                parent::__construct( '#__mediasyndicator_files', 'id', $db );
        }
}
