<?php
/**
 * @author VaL
 * @file leieTemplate.php
 * @copyright Copyright (C) 2011 VaL::bOK
 * @license GNU GPL v2
 * @package leie
 */

/**
 * Simplified object to handle templates
 */
class leieTemplate
{
    /**
     * Submitted variable list
     *
     * @var (string)
     */
    protected $VarList = array();

    /**
     * @param (string) $dir Where templates are located
     */
    public function __construct( $dir = 'design/templates' )
    {
        $this->Dir = $dir;
    }

    /**
     * Wrapper to create the object
     *
     * @return (__CLASS__)
     */
    public static function get( $dir = false )
    {
        return $dir ? new self( $dir ) : new self();
    }

    /**
     * Sets a var
     *
     * @param (string)
     * @param (mixed)
     * @return (void)
     */
    public function setVariable( $name, $value )
    {
        $this->VarList[$name] = $value;
    }

    /**
     * Gets a var
     *
     * @param (string)
     * @return (mixed|null)
     */
    public function getVariable( $name )
    {
        return isset( $this->VarList[$name] ) ? $this->VarList[$name] : null;
    }

    /**
     * Processes provided template
     *
     * @return (string)
     */
    public function fetch( $filename, $ttl = false )
    {
        $tpl = $this->Dir . '/' . $filename;
        if ( !file_exists( $tpl ) )
        {
            throw new leieRunTimeException( "Template '$tpl' does not exist" );
        }

        // @todo: Fix var names. It is due to if $_leie_template_var_name_100500 is passed as a variable
        // it will be overrided by this foreach
        foreach ( $this->VarList as $_leie_template_var_name_100500 => $_leie_template_var_value_100500 )
        {
            $$_leie_template_var_name_100500 = $_leie_template_var_value_100500;
        }

        ob_start();
        $result = include( $tpl );
        return ob_get_clean();
    }

    /**
     * Includes template
     *
     * @return (void)
     */
    public static function includeTemplate( $uri, $data = array() )
    {
        $tpl = new self();
        foreach ( $data as $key => $value )
        {
            $tpl->setVariable( $key, $value );
        }

        echo $tpl->fetch( $uri );
    }

    /**
     * Washes the content
     *
     * @return (string)
     */
    public static function wash( $content, $type = 'xhtml' )
    {
        switch ( $type )
        {
            default:
            case 'xhtml':
            {
                $result = htmlspecialchars( $content );
            } break;

            case 'javascript':
            {
                $result = str_replace( array( "\\", "\"", "'"),
                                       array( "\\\\", "\\042", "\\047" ) , $content );
            } break;
        }

        return $result;
    }
}
?>
