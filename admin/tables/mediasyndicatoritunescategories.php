<?php       

defined('_JEXEC') or die();

class JTableMediaSyndicatorItunesCategories extends JTable
{
        var $id 				= 0;
        var $parent_category_id	= null;
        var $published			= 1;
        var $name				= '';
        
        function __construct(&$db)
        {
                parent::__construct( '#__mediasyndicator_itunes_categories', 'id', $db );
        }
}
