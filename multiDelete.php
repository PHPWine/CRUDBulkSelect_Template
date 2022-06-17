<?php session_start(); ?>
<?php 

define('PHPWINE_DEBUG_ERRORS', true ); 

$bulk_crud = new class {

 /**
  * @var 
  * @property string null 
  * Defined : null property form appending on add more
  * @since v1.3.1.0
  * @since 03.11.2022
  **/
    private string|array $read;
        
    public function __construct() {
      
      $this->php_wine('autoload');

      new \PHPWineVanillaFlavour\Wine\Optimizer\Html;
      new \PHPWineVanillaFlavour\Wine\Optimizer\EnhancerElem;
      new \PHPWineVanillaFlavour\Wine\Optimizer\EnhancerAttr;
      new \PHPWineVanillaFlavour\Wine\Optimizer\HtmlDiv;
      new \PHPWineVanillaFlavour\Wine\Optimizer\HtmlForm;
      new \PHPWineVanillaFlavour\Wine\Optimizer\HtmlTable;
      new \PHPWineVanillaFlavour\Wine\Optimizer\HtmlTr;

      new \PHPWineVanillaFlavour\Plugins\PHPCrud\Crud\Wine\VanillaFetch;
      new \PHPWineVanillaFlavour\Plugins\PHPCrud\Crud\Wine\VanillaDelete;
      new \PHPWineVanillaFlavour\Plugins\PHPCrud\Crud\Wine\VanillaExtract;
      
      // session set!
      if( isset($_GET['delete-succesfully']) ) { echo $_SESSION['delete']; } 

      // execution
      $this->execute_bulk_selection();
      
    }

  /**
   * Defined: execute_bulk_selection 
   * @since PHPWine v1.4
   * @since PHPCrud Vanilla v1.3.1.0
   * @since 17.06.2022
   **/
    private function execute_bulk_selection() : void {

        $this->delete_persons_bulk();
        $this->fetch_persons_bulk();
        $this->select_delete_bulk_persons();
        $this->footer_section();

    }

  /**
   * Defined: fetch data from database 
   * @since PHPWine v1.4
   * @since PHPCrud Vanilla v1.3.1.0
   * @since 17.06.2022
   **/
     private function fetch_persons_bulk() : void {
      
            $this->read = wine_fetch( '', [ 'mixed' => [ "SELECT * FROM  Crud ORDER BY friend_id DESC " ]  ] 
                                
            ,function( $read )  {  
            
                $friends = array(); if( $read )  { foreach ($read as $value) {  
                    
                    $friends[] = ELEM('tr', [ CHILD => [
                        
                        ['td', ATTR  => ["scope" => "col"], INNER => [ 

                            ['input', ATTR => [
                                'type'  => 'checkbox',
                                'name'  => 'friends_id[]',
                                'value' => $value["friend_id"],
                            ]]
                            
                        ]],
                        ['td', ATTR  => [ "scope" => "col" ], VALUE => [ $value["friend_id"]   ]],
                        ['td', ATTR  => [ "scope" => "col" ], VALUE => [ $value["friend_name"] ]],
                        ['td', VALUE => [ $value["friend_mobile"] ]],
                        ['td', VALUE => [ $value["friend_email"]  ]]
        
                    ]]);
                
                }  return (array)  $friends ;
            
            } 
                
            return []; // if there is not post return empty array! 
            
         });
   
     }

  /**
   * Defined: delete functionality 
   * @since PHPWine v1.4
   * @since PHPCrud Vanilla v1.3.1.0
   * @since 17.06.2022
   **/
     private function delete_persons_bulk() : void {
      
        if( isset($_POST['friend_delete']) == true ) :
   
            $selected_friends =  $_POST['friends_id'];
            $friend_ids       = implode(',' , $selected_friends);
            
            wine_delete( '', [
            
            'crud',
            'condition' => [" WHERE friend_id IN( $friend_ids ) "] 
          
           ], function ( $delete_bulk ) { if( $delete_bulk )  {
        
              $_SESSION['delete'] = 'Succesfully Friend Deleted !';
        
              header("location: multiDelete.php?delete-succesfully");
        
           }});
          
        endif; 
   
     }

  /**
   * Defined: elect bulk delete functionality 
   * @since PHPWine v1.4
   * @since PHPCrud Vanilla v1.3.1.0
   * @since 17.06.2022
   **/
     private function select_delete_bulk_persons() : void {

        print form(function() {

            return table([ CHILD => [

                ['tbody', VALUE => [ tr([ CHILD => [

                    ['th', INNER => [
                        ['input', ATTR => ['type'=>'submit','name'=>'friend_delete','value'=>'Delete']]
                    ]],
                    ['th', VALUE => ['ID']],
                    ['th', VALUE => ['Friend Name : ']],
                    ['th', VALUE => ['Friend Email : ']],
                    ['th', VALUE => ['Friend Mobile : ']],
                    
                 ]])                    

               ]],
               ['tbody', VALUE => [ wine_extract($this->read)] ],

            ]]);

        },[['method'],['POST']]);

     }

  /**
   * Defined: ftr JS installation
   * @since PHPWine v1.4
   * @since PHPCrud Vanilla v1.3.1.0
   * @since 17.06.2022
   **/  
     private function footer_section() : void {

        print ATTR( 'SCRIPT' , [
           
            'jQuery' => [
            
               'src'          => 'https://code.jquery.com/jquery-3.6.0.slim.min.js'
             , 'integrity'    => 'sha256-u7e5khyithlIdTpu22PHhENmPcRdFiHRjhAuHcs05RI='
             , 'crossorigin'  => 'anonymous'
         
            ]
         
        ]);

    }

  /**
   * Defined: PHPWine Loader
   * @since PHPWine v1.4
   * @since PHPCrud Vanilla v1.3.1.0
   * @since 17.06.2022
   **/ 
    private function php_wine(string $autoload) : void {

      require dirname(__FILE__) . DIRECTORY_SEPARATOR .'vendor/' . $autoload.'.'.'php';

    }

 }; 

 /**
 * 
 * Would you like me to treat a cake and coffee ?
 * Become a donor, Because with you! We can build more...
 * Donate:
 * GCash : +639650332900
 * Paypal account: syncdevprojects@gmail.com
 * 
 **/



