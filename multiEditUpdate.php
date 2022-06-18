<?php session_start(); ?>
<?php 

define('PHPWINE_DEBUG_ERRORS', true ); 

$bulk_crud = new class {

   private array $edit_id;
        
   public function __construct() {
     
     $this->php_wine('autoload');

     new \PHPWineVanillaFlavour\Wine\Optimizer\Html;
     new \PHPWineVanillaFlavour\Wine\Optimizer\EnhancerElem;
     new \PHPWineVanillaFlavour\Wine\Optimizer\HtmlForm;
     new \PHPWineVanillaFlavour\Wine\Optimizer\HtmlTable;
     new \PHPWineVanillaFlavour\Wine\Optimizer\HtmlButton;

     new \PHPWineVanillaFlavour\Plugins\PHPCrud\Crud\Wine\VanillaFetch;
     new \PHPWineVanillaFlavour\Plugins\PHPCrud\Crud\Wine\VanillaUpdate;
     new \PHPWineVanillaFlavour\Plugins\PHPCrud\Crud\Wine\VanillaExtract;

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

       $this->select_update_persons_bulk();
       $this->update_persons_bulk();

    }

  /**
   * Defined: fetch data from database 
   * @since PHPWine v1.4
   * @since PHPCrud Vanilla v1.3.1.0
   * @since 17.06.2022
   **/
  private function select_update_persons_bulk() : void {

    if(isset($_REQUEST['bulkEditAll']) == true ) : 

      $this->edit_id  = $_POST['bulkEditId'];

      $this->fetch_persons_bulk( $this->edit_id );
     
  
    endif;
  
  }

  /**
   * Defined: fetch data from database 
   * @since PHPWine v1.4
   * @since PHPCrud Vanilla v1.3.1.0
   * @since 17.06.2022
   **/
  private function update_persons_bulk() : void {

    if(isset($_REQUEST['bulkUpdate']) == true ) : 

       $bulkUpdateIds  = $_POST['bulkUpdateId']; 

       for( $inputData = 0; $inputData < count($_POST['bulkUpdateId']); $inputData++ ) {
 
        wine_update('crud', [
      
          'friend_name'   => $_POST['friend_name'][$inputData],
          'friend_mobile' => $_POST['friend_mobile'][$inputData],
          'friend_email'  => $_POST['friend_email'][$inputData],
      
          'condition'     => [' WHERE friend_id = '. $bulkUpdateIds[$inputData] ]
    
      ], function( $do_update ) { if( $do_update ) {

          $_SESSION['update'] = 'Succesfully Friend Updated !';

          header("location: multiSelectEdit.php?update-succesfully");         

        } 
  
      });
         
    }
  
   endif;
  
  }
  
  /**
   * Defined: fetch data from database 
   * @since PHPWine v1.4
   * @since PHPCrud Vanilla v1.3.1.0
   * @since 17.06.2022
   **/
  private function fetch_persons_bulk( array $getPost = [] ) : void {
    
    print form(function() use ($getPost) { $print = '';

      foreach( $getPost as $key_bulk => $val_bulk ) { 

       $print .= wine_extract(wine_fetch( '', [ 'mixed' => [ "SELECT * FROM `crud` WHERE `friend_id`='$val_bulk' " ]], function( $read )  {  
                
                $friends = array(); if( $read )  { foreach ($read as $value) {  
    
                  $friends[] = table(function() use ($value)  {
    
                    return ELEM('tr', [ CHILD => [
                        
                      ['td', INNER => [
    
                        ['input', ATTR => ['type' => 'text', 'name' => 'friend_name[]',   'value' => $value["friend_name"]]],
                        ['input', ATTR => ['type' => 'text', 'name' => 'friend_mobile[]', 'value' => $value["friend_mobile"]]],
                        ['input', ATTR => ['type' => 'text', 'name' => 'friend_email[]',  'value' => $value["friend_email"]]],
    
                      ]],
                      ['td', INNER => [ 
    
                        ['input', ATTR => ['type'  => 'hidden', 'name' => 'bulkUpdateId[]', 'value' => $value["friend_id"]]],
    
                      ]]
    
                  ]]);
    
                  });
                
                }  return (array)  $friends ;
            
            } 
                
            return []; // if there is not post return empty array! 
            
          }));   
    
       }
       
       $print .= button('Update Friends',[['type','name','id'],['submit','bulkUpdate','bulkUpdate']]);

       return ($print);

    },[['method'],['POST']]);

}

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


  