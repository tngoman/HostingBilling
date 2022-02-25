<?php

if( ! function_exists('update_all_module_headers'))
{
    /**
     * Shortcut to Module::update_all_module_headers()
     *
     * Executes update_module_headers() for all Module in database
     *
     * @since   0.1.0
     * @return bool
     */
    function update_all_module_headers()
    {
        return Module::$instance->update_all_module_headers();
    }
}

// ------------------------------------------------------------------------

if( ! function_exists('update_module_headers'))
{
    /**
     * Shortcut to Module::update_module_headers()
     *
     * Updates the module headers for a specified module based on the Module .php file comments
     *
     * @param   string $module Module system name
     *
     * @since   0.1.0
     * @return  bool
     */
    function update_module_headers( $module )
    {
        return Module::$instance->update_module_headers( $module );
    }
}

// ------------------------------------------------------------------------

if( ! function_exists('install_module'))
{
    /**
     * Shortcut to Module::install_module()
     *
     * Executes whatevers in the Module install method
     *
     * @param   string $module Module system name
     *
     * @since   0.1.0
     * @return  bool
     */
    function install_module( $module, $data = NULL )
    {
        return Module::$instance->install_module( $module, $data );
    }
}

// ------------------------------------------------------------------------

if( ! function_exists('enable_module'))
{
    /**
     * Shortcut to Module::enable_module()
     *
     * Enable a specified module by setting the database status value to 1
     *
     * @param   string $module Module system name
     * @param   mixed  $data   Any data that should be handed down to the Module activation method (optional)
     *
     * @since   0.1.0
     * @return  bool
     */
    function enable_module( $module, $data = NULL )
    {
        return Module::$instance->enable_module( $module, $data );
    }
}

// ------------------------------------------------------------------------

if( ! function_exists('disable_module'))
{
    /**
     * Shortcut to Module::disable_module()
     *
     * Disable a specified module by setting the database status value to 0
     *
     * @param   string $module Module system name
     * @param   mixed  $data   Any data that should be handed down to the Module deactivate method (optional)
     *
     * @since   0.1.0
     * @return  bool
     */
    function disable_module( $module, $data = NULL )
    {
        return Module::$instance->disable_module( $module, $data );
    }
}

// ------------------------------------------------------------------------

if( ! function_exists('module_details'))
{
    /**
     * Shortcut to Module::module_details()
     *
     * Return the details of a module from the Module database table
     *
     * @param   string $module Module system name
     *
     * @since   0.1.0
     * @return  array
     */
    function module_details( $module )
    {
        return Module::$instance->module_details( $module );
    }
}

// ------------------------------------------------------------------------

if( ! function_exists('get_messages'))
{
    /**
     * Shortcut to Module::get_messages()
     *
     * Gets all the module messages thus far (errors, debug messages, warnings)
     *
     * @param   string $type Specific type to retrieve, if NULL, all are returned
     *
     * @since   0.1.0
     * @return  array
     */
    function get_messages( $type = NULL )
    {
        return Module::$instance->get_messages( $type );
    }
}

// ------------------------------------------------------------------------

if( ! function_exists('print_messages'))
{
    /**
     * Shortcut to Module::print_messages()
     *
     * Displays all the module messages thus far (errors, debug messages, warnings)
     *
     * @param   string $type Specific type to retrieve, if NULL, all are printed
     *
     * @since   0.1.0
     * @return  array
     */
    function print_messages( $type = NULL )
    {
        return Module::$instance->print_messages( $type );
    }
}

// ------------------------------------------------------------------------

if( ! function_exists('get_orphaned_Module'))
{
    /**
     * Shortcut to Module::get_orphaned_Module()
     *
     * See if there are any Module in the Module directory that arent in the database
     *
     * @since   0.1.0
     * @return  array
     */
    function get_orphaned_Module()
    {
        return Module::$instance->get_orphaned_Module();
    }
}

// ------------------------------------------------------------------------

if( ! function_exists('add_action'))
{
    /**
     * Shortcut to Module::add_action()
     *
     * Add an action - a function that will fire off when a tag/action is executed (NOT
     * the same as add_filter - which will return a value
     *
     * @param   string       $tag      Tag/Action thats being executed
     * @param   string|array $function Either a single function (string), or a class and method (array)
     * @param   int          $priority Priority of this action
     *
     * @since   0.1.0
     * @return  boolean
     */
    function add_action( $tag, $function, $priority = 10 )
    {
        return Module::$instance->add_action( $tag, $function, $priority );
    }
}

// ------------------------------------------------------------------------

if( ! function_exists('add_filter'))
{
    /**
     * Shortcut to Module::add_filter()
     *
     * Add a filter - a function that can be used to effect/parse/filter out some content (NOT
     * the same as add_action - which will just fire off a function
     *
     * @param   string       $tag      Tag/Action thats being executed
     * @param   string|array $function Either a single function (string), or a class and method (array)
     * @param   int          $priority Priority of this action
     *
     * @since   0.1.0
     * @return  boolean
     */
    function add_filter( $tag, $function, $priority = 10 )
    {
        return Module::$instance->add_filter( $tag, $function, $priority );
    }
}

// ------------------------------------------------------------------------

if( ! function_exists('get_actions'))
{
    /**
     * Shortcut to Module::get_actions()
     *
     * Retrieve all actions/filters that are assigned to actions/tags
     *
     * @since   0.1.0
     * @return  array
     */
    function get_actions()
    {
        return Module::$instance->get_actions();
    }
}

// ------------------------------------------------------------------------

if( ! function_exists('retrieve_Module'))
{
    /**
     * Shortcut to Module::retrieve_Module()
     *
     * Retrieve all Module
     *
     * @since   0.1.0
     * @return  array
     */
    function retrieve_Module()
    {
        return Module::$instance->retrieve_Module();
    }
}

// ------------------------------------------------------------------------

if( ! function_exists('do_action'))
{
    /**
     * Shortcut to Module::do_action()
     *
     * Execute all module functions tied to a specific tag
     *
     * @param   string $tag  Tag to execute
     * @param   mixed  $args Arguments to hand to module (Can be anything)
     *
     * @since   0.1.0
     * @return  mixed
     */
    function do_action( $tag, array $args = NULL )
    {
        //log_message('error',"Doing $tag " . ($args ? "With args: " . serialize($args) : "With no args"));
        return Module::$instance->do_action( $tag, $args );
    }
}

// ------------------------------------------------------------------------

if( ! function_exists('remove_action'))
{
    /**
     * Shortcut to Module::remove_action()
     *
     * Remove a specific module function assigned to execute on a specific tag at a specific priority
     *
     * @param   string       $tag      Tag to clear actions from
     * @param   string|array $function Function or object/method to remove from tag
     * @param   int          $priority Priority to clear
     *
     * @since   0.1.0
     * @return  boolean
     */
    function remove_action( $tag, $function, $priority = 10 )
    {
        return Module::$instance->remove_action( $tag, $function, $priority );
    }
}

// ------------------------------------------------------------------------

if( ! function_exists('current_action'))
{
    /**
     * Shortcut to Module::current_action()
     *
     * Get the current module action being executed
     *
     * @since   0.1.0
     * @return  string
     */
    function current_action()
    {
        return Module::$instance->current_action();
    }
}

// ------------------------------------------------------------------------

if( ! function_exists('has_run'))
{
    /**
     * Shortcut to Module::has_run()
     *
     * See if an action has run or not
     *
     * @param   string $action Tag/action to check
     *
     * @since   0.1.0
     * @return  boolean
     */
    function has_run( $action = NULL )
    {
        return Module::$instance->has_run( $action );
    }
}

// ------------------------------------------------------------------------

if( ! function_exists('doing_action'))
{
    /**
     * Shortcut to Module::doing_action()
     *
     * If no action is specified, then the current action being executed will be returned, if
     * an action is specified, then TRUE/FALSE will be returned based on if the action is
     * being executed or not
     *
     * @oaran   string  $action     Action to check
     * @since   0.1.0
     * @return  boolean|string
     */
    function doing_action( $action = NULL )
    {
        return Module::$instance->doing_action( $action );
    }
}

// ------------------------------------------------------------------------

if( ! function_exists('did_action'))
{
    /**
     * Shortcut to Module::did_action()
     *
     * Check if an action/tag has been executed or not
     *
     * @param   string $tag Tag/action to check
     *
     * @since   0.1.0
     * @return  boolean
     */
    function did_action( $tag )
    {
        return Module::$instance->did_action( $tag );
    }
}
