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
    public ?string $person; 

    public function __construct() {
      
      $this->php_wine('autoload');
    
      new \PHPWineVanillaFlavour\Wine\Optimizer\Html;
      new \PHPWineVanillaFlavour\Wine\Optimizer\EnhancerElem; 
      new \PHPWineVanillaFlavour\Wine\Optimizer\EnhancerHLine;
      new \PHPWineVanillaFlavour\Wine\Optimizer\EnhancerAttr;
      new \PHPWineVanillaFlavour\Wine\Optimizer\HtmlDiv;
      new \PHPWineVanillaFlavour\Wine\Optimizer\HtmlForm;
      new \PHPWineVanillaFlavour\Wine\Optimizer\HtmlTable;
      new \PHPWineVanillaFlavour\Wine\Optimizer\HtmlTr;
      new \PHPWineVanillaFlavour\Wine\Optimizer\HtmlButton;

      new \PHPWineVanillaFlavour\Plugins\PHPCrud\Crud\Wine\VanillaCreate;
      new \PHPWineVanillaFlavour\Plugins\PHPCrud\Crud\Wine\VanillaFetch;
      new \PHPWineVanillaFlavour\Plugins\PHPCrud\Crud\Wine\VanillaUpdate;
      new \PHPWineVanillaFlavour\Plugins\PHPCrud\Crud\Wine\VanillaDelete;
      new \PHPWineVanillaFlavour\Plugins\PHPCrud\Crud\Wine\VanillaExtract;

      // session set!
      if( isset($_GET['create-succesfully']) ) { echo $_SESSION['create']; } 
      // session set!
      if( isset($_GET['update-succesfully']) ) { echo $_SESSION['update']; } 
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
      
      // Fetch and Select Edit process !
      $this->fetch_persons_bulk();
      $this->select_edit_update_bulk_persons();       
      
      // Add new bulk friends
      $this->insert_new_person();
      $this->add_new_person();

      // Update friends current selected
      $this->select_update_persons_bulk();
      $this->update_persons_bulk();

      // Delete friend current selected
      $this->delete_persons_bulk();

      $this->footer_section();
      
    }

  /**
   * Defined: fetch data from database 
   * @since PHPWine v1.4
   * @since PHPCrud Vanilla v1.3.1.0
   * @since 17.06.2022
   **/
  private function fetch_persons_bulk() : void {
      
    $this->read = wine_fetch( '', [ 'mixed' => [ "SELECT * FROM  Crud ORDER BY friend_id DESC " ] ], function( $read )  {  
    
        $friends = array(); if( $read )  { foreach ($read as $value) {  
            
            $friends[] = ELEM('tr', [ CHILD => [
                
                ['td', ATTR  => ["scope" => "col"], INNER => [ 

                    ['input', ATTR => [
                           'type'  => 'checkbox',
                           'name'  => 'friends_id[]',
                           'value' => $value["friend_id"],
                       ]
                       
                   ]
                   
               ]],
                ['td', ATTR  => ["scope" => "col"], INNER => [ 

                    ['input', ATTR => [
                        'type'  => 'checkbox',
                        'name'  => 'bulkeditid[]',
                        'value' => $value["friend_id"],
                        'class' => 'bulkEditFriend'
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
    * Defined: select_edit_update_bulk_persons 
    * @since PHPWine v1.4
    * @since PHPCrud Vanilla v1.3.1.0
    * @since 17.06.2022
    **/
   private function select_edit_update_bulk_persons() : void {
 
    print form(function() {
 
        return table([ CHILD => [
 
            ['tbody', VALUE => [ tr([ CHILD => [
                
                ['th', INNER => [
                    ['input', ATTR => ['type'=>'submit','name'=>'friend_delete','value'=>'Delete']]
                ]],
                ['th', INNER => [
                    ['div', VALUE => [ div([ CHILD => [
                      
                      ['input',  ATTR => ['type'=>'checkbox','id'=>'bulkEdit']],
                      ['button', ATTR => ['type'=>'submit','name'=>'bulkEditAll','id'=>'bulkEditAll'], VALUE => ['Edit'] ]
 
                    ]]) ]]
                    
                ]],
                ['th', VALUE => ['ID']],
                ['th', VALUE => ['Friend Name : ']],
                ['th', VALUE => ['Friend Email : ']],
                ['th', VALUE => ['Friend Mobile : ']],
                
             ]])                    
 
           ]],
           ['tbody', VALUE => [ wine_extract($this->read)] ],
 
        ]]);
 
    },[['action','method'],['vanilla-crud-bulk.php','POST']]); // multiEditUpdate
 
  }
 

  /**
   * Defined: insert new data to database with arrays of datas
   * @since PHPWine v1.4
   * @since PHPCrud Vanilla v1.3.1.0
   * @since 17.06.2022
   **/    
    private function insert_new_person() : void {
      
      if(isset($_REQUEST['insertData']) == true ) {
 
       for ( $inputData = 0; $inputData < count($_POST['friend_name']) ; $inputData++ ) { 
             
           if( !empty( $create  = wine_creates( 'crud' , [ 
           
             'friend_name'   => '?',
             'friend_mobile' => '?',
             'friend_email'  => '?'
       
           ], "sss", array(
           
             $_POST['friend_name'][$inputData]   ?? '', 
             $_POST['friend_mobile'][$inputData] ?? '', 
             $_POST['friend_email'][$inputData]  ?? '' 
       
           )))) { 
   
             $_SESSION['create'] = "Last_id : " . $create . " Added new record! ";  
   
             header("location: vanilla-crud-bulk.php?create-succesfully"); 
                                     
           } 
   
        }
     
     }
 
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
    
          header("location: vanilla-crud-bulk.php?delete-succesfully");
    
       }});
      
    endif; 

 }

  /**
   * Defined: form add new person
   * @since PHPWine v1.4
   * @since PHPCrud Vanilla v1.3.1.0
   * @since 17.06.2022
   **/   
    private function add_new_person() : void {

      print form( function() { 
        
        $form_new_person = div(function() {

           $this->person = div([ CHILD => [

              ['div', INNER => [
                  
                  ['label', VALUE => ['Friend name : ']],
                  ['input', ATTR  => ['type' => 'text','name' => 'friend_name[]']]
      
              ]],
              ['div', INNER => [
                  
                  ['label', VALUE => ['Friend mobile : ']],
                  ['input', ATTR  => ['type' => 'text','name' => 'friend_mobile[]']]
      
              ]],
              ['div', INNER => [
                  
                  ['label', VALUE => ['Friend email : ']],
                  ['input', ATTR  => ['type' => 'text','name' => 'friend_email[]']]
      
              ]],
    
           ]],[['class'],['forms-request']]); return ($this->person);

        },[['class'],['add_friend_form']]);

        $btn_insert_data = div([ CHILD => [
            
            ['input', ATTR => [
                'type' => 'submit', 'name' => 'insertData',
                'id'   => 'btn-submit'
            ]],
            ['input', ATTR => [
                'type' => 'button', 'value'=> 'Add more',
                'id'   => 'btn-addmore'
            ]],

        ]]);

        return ($form_new_person . $btn_insert_data);

      }, [['method'],['POST']]);

    }

 /**
   * Defined: fetch data from database 
   * @since PHPWine v1.4
   * @since PHPCrud Vanilla v1.3.1.0
   * @since 17.06.2022
   **/
  private function select_update_persons_bulk() : void {

    if(isset($_REQUEST['bulkEditAll']) == true ) : 

      $this->edit_id  = $_POST['bulkeditid'];

      $this->update_fetch_persons_bulk( $this->edit_id );
     
  
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

          header("location: vanilla-crud-bulk.php?update-succesfully");         

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
  private function update_fetch_persons_bulk( array $getPost = [] ) : void {
    
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
       
      ]); ?>
            
  <script>
  
  // Add more button add more fields friends
  $('document').ready(function() {

   $('#btn-addmore').click(function(e) { e.preventDefault();

     $('.add_friend_form').append(`<?php echo __HR() . $this->person; ?>`);
  
   });
      
  });
   
  // Edit Select all checkbox
  $(document).ready(function(){

    $('#bulkEdit').on('click', function(){
     if($('#bulkEdit:checked').length == $('#bulkEdit').length) { $('.bulkEditFriend').prop('checked',true); } 
     else { $('.bulkEditFriend').prop('checked',false); }
    });

  });

  </script>
       
  <?php }

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

