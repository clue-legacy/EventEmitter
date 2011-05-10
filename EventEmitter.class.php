<?php 

/**
 * 
 * @author mE
 * @link https://github.com/Wolfy87/EventEmitter
 * @see Mootools/Class.Extras.js
 * @license MIT-style license
 * @version 0.1.0
 */
class EventEmitter{
    /**
     * array holding arrays of event handler functions 
     * 
     * @var array
     */
    protected $events;
    
    /**
     * instanciate new EventEmitter
     */
    public function __construct(){
        $this->events = array();
    }
    
    /**
     * 
     *  
     * @param string   $name    the type of event to wait for
     * @param callback $handler callback function to execute
     * @return EventEmitter $this (chainable)
     */
    public function addEvent($name,$handler){
        if(!isset($this->events[$name])){
            $this->events[$name] = array($handler);
        }else{
            $this->events[$name][] = $handler;
        }
    }
    
    /**
     * The same as addEvent, but accepts an array to add multiple events at once.
     * 
     * @param array $events An object with key/value representing: key the event name (e.g. 'start'), and value the function that is called when the Event occurs.
     * @return EventEmitter $this (chainable)
     */
    public function addEvents($events){
        foreach($events as $name=>$handler){
            $this->addEvent($name,$handler);
        }
        return $this;
    }
    
    /**
     * 
     * 
     * @param string        $name
     * @param callback|NULL $handler
     * @return EventEmitter $this (chainable)
     */
    public function removeEvent($name,$handler=NULL){
        if(isset($this->events[$name])){
            if($handler === NULL){
                unset($this->events[$name]);
            }else{
                foreach($this->events[$name] as $key=>$fn){
                    if($fn === $handler){
                        unset($this->events[$key]);
                    }
                }
                if(!$this->events[$name]){
                    unset($this->events[$name]);
                }
            }
        }
        return $this;
    }
    
    /**
     * Fires all events of the specified type
     * 
     * @param string $name
     * @return EventEmitter $this (chainable)
     */
    public function fireEvent($name){
        if(isset($this->events[$name])){
            $args = func_get_args();
            array_shift($args);
            
            foreach($this->events[$name] as $event){
                call_user_func_array($event,$args);
            }
        }
        return $this;
    }
    
    ////////////////////////////////////////////////////////////////////////////
    // alias definitions
    
    public function on($name,$handler){
        return $this->addEvent($name,$handler);
    }
    
    public function addListener($name,$handler){
        return $this->addEvent($name,$handler);
    }
    
    public function removeListener($name,$handler=NULL){
        return $this->removeEvent($name,$handler);
    }
    
    public function emit($name){
        if(isset($this->events[$name])){
            $args = func_get_args();
            call_user_func_array(array($this,'fireEvent'),$args);
        }
        return $this;
    }
}