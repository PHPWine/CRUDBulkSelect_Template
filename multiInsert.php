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

      new \PHPWineVanillaFlavour\Plugins\PHPCrud\Crud\Wine\VanillaCreate;

      // session set!
      if( isset($_GET['create-succesfully']) ) { echo $_SESSION['create']; } 

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

      $this->insert_new_person();
      $this->add_new_person();
      $this->footer_section();

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
   
             header("location: multiInsert.php?create-succesfully"); 
                                     
           } 
   
        }
     
     }
 
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

  $('document').ready(function() {

   $('#btn-addmore').click(function(e) { e.preventDefault();

     $('.add_friend_form').append(`<?php echo __HR() . $this->person; ?>`);
  
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

