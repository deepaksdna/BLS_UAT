<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\ORM\TableRegistry;
use Cake\Routing\Router;
use Cake\I18n\Number;
/**
 * Products Controller
 *
 * @property \App\Model\Table\ProductsTable $Products
 */
class ProductsController extends AppController
{
	
	public $helpers = ['Form', 'Html'];
	public function initialize()
		{
			parent::initialize();
			
			// Set the layout
			$this->viewBuilder()->layout('admin');
			$this->loadComponent('Cewi/Excel.Import');
		}
    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index()
    {	
		  $this->paginate = [
            'contain' => ['ProductsAttrs.Brands', 'ProductsCategories.Categories', 'ProductsCategories.Categories.ParentCategories'],
			'sortWhitelist' => ['item_code','product_desc','Brands.name','ParentCategories.name', 'Categories.name', 'status']
        ];	
        $products = $this->paginate($this->Products);

        $this->set(compact('products'));
        $this->set('_serialize', ['products']);
		

	
    }
	
    /**
     * View method
     *
     * @param string|null $id Product id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $product = $this->Products->get($id, [
            'contain' => [ 'Categories', 'ProductsAttrs','ProductsAttrs.Brands', 'ProductsImages','ProductsImages.Colors' , 'ProductsMarketingImages', 'ProductsPrices', 'ProductsRelateds', 'ProductsRelateds.relatedproduct1', 'ProductsRelateds.relatedproduct2', 'ProductsRelateds.relatedproduct3']
        ]);
        $this->set('product', $product);
        $this->set('_serialize', ['product']);
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {	$Brands = TableRegistry::get('Brands');
		$Categories = TableRegistry::get('Categories');	
		$Configrations = TableRegistry::get('Configrations');
		
		
		$product = $this->Products->newEntity();
    if ($this->request->is('post')) {
	
		
	//Product Code Genration	
		//1st Phase
		$cateID = $this->request->data['products_category']['category_id'];
		$CatString = $Categories->get($cateID);
		$firstPhase = substr(strtoupper($CatString->name), 0,2);
		$firstPhase = $firstPhase.$cateID;
		//2nd Phase
		$brandID = $this->request->data['products_attr']['brand_id'];
		$BrandString = $Brands->get($brandID);
		$secondPhase = substr(strtoupper($BrandString->name), 0,2); 
		$secondPhase = $secondPhase.$brandID;
		//4rd Phase
		$CountPhase = count($this->Products->find('all')->toArray())+1;
		
		
		
		//Final Item Code
		$itemCode = $firstPhase.$secondPhase.'-'.$CountPhase; 
		
		if($this->request->data['products_image']['image']['name']!=''){
		
		$ColorId = $this->request->data['products_image']['color_id'];
		$colorString = $this->Products->ProductsImages->Colors->get($ColorId);
		$ColorPhase = substr(strtoupper($colorString->name), 0,2);
		$ColorPhase = $ColorPhase.$ColorId; 
		
		//Final Product Code
		$productCode = $firstPhase.$secondPhase.'-'.$CountPhase.$ColorPhase;
		$this->request->data['products_image']['product_code']= $productCode ;
		$this->request->data['products_image']['status']=1;	
		}

		$this->request->data['item_code']= $itemCode ;
		$this->request->data['status']='N';
		
		$item_codes[]=$this->request->data['products_related']['relatedproduct1']['item_code'];		
		$item_codes[]=$this->request->data['products_related']['relatedproduct2']['item_code'];		
		$item_codes[]=$this->request->data['products_related']['relatedproduct3']['item_code'];		



		foreach($item_codes as $key => $item_code){
		$key++;
		$query = $this->Products->find('all', ['conditions'=>['products.item_code'=>$item_code], 'fields'=>['id','item_code']]);
		$related_data = $query->toArray();
					if(count($related_data)>0){
					$this->request->data['products_related']['related_product_'.$key]= $related_data[0]['id'];	
					}else
					{
						$this->request->data['products_related']['related_product_'.$key]=0;
					}
		}
	$this->request->data['products_attr']['video_link'] = 'http://'.$this->request->data['products_attr']['video_link'];
	$product = $this->Products->patchEntity($product, $this->request->data,[
			'associated' => ['ProductsAttrs', 'Categories', 'ProductsAttrs', 'ProductsPrices','ProductsRelateds', 'ProductsCategories']
			]);  
	

	$result = $this->Products->save($product);
			if ($result){ 
				
							$id = $result->id;	
							$ProductsMarketingImages = TableRegistry::get('ProductsMarketingImages');
							$ProductsImages = TableRegistry::get('ProductsImages');							
							$count=1;	
				//Save Marketing images			
							foreach($this->request->data['products_marketing_image'] as $filekey => $file){
									
								foreach($file['image'] as $image){
									$this->request->data['products_marketing_image'][$count]['image_dir'] = $file['image_dir'];
									$this->request->data['products_marketing_image'][$count]['product_id']= $id;
									$this->request->data['products_marketing_image'][$count]['image'] = $image;
									$count++; 									
								}					
							}			
							unset($this->request->data['products_marketing_image']['file']);
								foreach($this->request->data['products_marketing_image'] as $image_photo){
									$aa = $ProductsMarketingImages->newEntity();
									$aa = $ProductsMarketingImages->patchEntity($aa,$image_photo);
									$ProductsMarketingImages->save($aa);
								} 			
				//Save Colored images
								if($this->request->data['products_image']['image']['name']!=''){
									$this->request->data['products_image']['product_id']= $id;
									$ab = $ProductsImages->newEntity();
									$ab = $ProductsImages->patchEntity($ab,$this->request->data['products_image']);
									$ProductsImages->save($ab);	
								}
				$this->Flash->success(__('The product has been saved.'));	
				return $this->redirect(['action' => 'index']);
			} else {
				
							$this->Flash->error(__('The product could not be saved. Please, try again.'));
			} 	
	}
		
		$configs = $Configrations->find();
		$config =  $configs->first();
		$main_promos[0]='';
		$main_promos[$config->promo_page_1]=$config->promo_page_1;
		$main_promos[$config->promo_page_2]=$config->promo_page_2;
		$main_promos[$config->promo_page_3]=$config->promo_page_3;
		

		$brands = $Brands->find('list', ['limit' => 200]);
		$colorsAll = $this->Products->ProductsImages->Colors->find('all', ['limit' => 200]);
		 
		
		$colors = array();
		$path = Router::url('/', true);
		foreach($colorsAll as  $colr){
			$colors[$colr->id]['image'] =  '<img src="'.$path.'img/files/Colors/image/'.$colr->image.'" width="20px" height="20px">';
			$colors[$colr->id]['name'] = $colr->name;
			$colors[$colr->id]['id'] = $colr->id;
		}
		
	
		
		$parentCategory = $this->Products->Categories->find('all')
		->where(['parent_id IS' =>NULL])
		->toArray();
		
		
		$categories=$this->Products->Categories->find('all')
		->contain('ChildCategories')
		->where(['parent_id IS' =>$parentCategory[0]->id])
		->toArray(); 
		
		$AllCategories=array();
		foreach($categories as $category){
			
			foreach($category['child_categories'] as $key =>$subCat){
					$AllCategories[$category['name']][$subCat['id']] = $subCat['name'];
				} 
		}
		
       /*  $categories = $this->Products->Categories->find('treeList', [
		  'spacer' => '.......'
		])->toArray();
		 */
		
        $this->set(compact('product','AllCategories','colors', 'brands', 'main_promos'));
        $this->set('_serialize', ['product']);
    }
	
	
public function bulkadd(){
		 $error=""; 
		 $Categories = TableRegistry::get('Categories');
		  $Logs = TableRegistry::get('Logs');
		 $Colors = TableRegistry::get('Colors');
		 $Brands = TableRegistry::get('Brands');
		
		
if ($this->request->is('post')) {
$datas = $this->Import->prepareEntityData($this->request->data['content']['tmp_name']);
foreach($datas as $key => $dataArray){
		
//Convert inputs to variables sTARTS hERE
		//pRODUCTS tABLE
		$this->request->data['item_code']=$dataArray['Item Code'];
		$this->request->data['product_desc'] = $dataArray['Detailed Description'];
		$this->request->data['title'] = $dataArray['Product Description'];
		$this->request->data['status'] = $dataArray['Unavailable Flag'];
		//pRODUCT aTTS tABLE
		$this->request->data['products_attr']['model'] = $dataArray['Model'];
		$this->request->data['products_attr']['video_link'] = $dataArray['Product Video Link'];
		$this->request->data['products_attr']['size'] = $dataArray['Size'];
		$this->request->data['products_attr']['weight'] = $dataArray['Weight'];
		$this->request->data['products_attr']['packaging'] = $dataArray['Packaging'];
		$this->request->data['products_attr']['uom'] = $dataArray['UOM'];
		$this->request->data['products_attr']['quantity'] = $dataArray['Quantity'];
		
		
		if(!empty($dataArray['Main Promo 1'])){
		$this->request->data['products_attr']['main_promo_1'] = $dataArray['Main Promo 1'];	
		}else{
		$this->request->data['products_attr']['main_promo_1'] = 0;
		}
		
		if(!empty($dataArray['Main Promo 2'])){
		$this->request->data['products_attr']['main_promo_2'] = $dataArray['Main Promo 2'];	
		}else{
		$this->request->data['products_attr']['main_promo_2'] = 0;
		}
		
		if(!empty($dataArray['Main Promo 3'])){
		$this->request->data['products_attr']['main_promo_3'] = $dataArray['Main Promo 3'];	
		}else{
		$this->request->data['products_attr']['main_promo_3'] = 0;
		}
			
		
		
		
		//pRODUCT pRICES tABLE
		$price = $dataArray['List Price (Price without GST)'];
		$price_without_sign = str_replace('$','', $price);
		$this->request->data['products_price']['list_price'] = $price_without_sign;
		
		//pRODUCT rELATED tABLE
		$this->request->data['products_related']['relatedproduct1']['item_code'] = $dataArray['Related Product 1'];
		$this->request->data['products_related']['relatedproduct2']['item_code'] = $dataArray['Related Product 2'];
		$this->request->data['products_related']['relatedproduct3']['item_code'] = $dataArray['Related Product 3'];
		
		//pRODUCT mARKETING images Table
		$this->request->data['products_marketing_image1'] = $dataArray['Product Image 1'];
		$this->request->data['products_marketing_image2'] = $dataArray['Product Image 2'];
		$this->request->data['products_marketing_image3'] = $dataArray['Product Image 3'];
		$this->request->data['products_marketing_image4'] = $dataArray['Product Image 4'];
		
	
			
//////////////////////////////////////////////////////////		
//Check Exsisting CATEgories , Sub Categories, Brands		
/////////////////////////////////////////////////////////
		$key = $key+1;
				$exists_cats = $Categories->exists(['name' => $dataArray['Category - Main']]);
				if($exists_cats){
					$Cats = $Categories->find()->where(['name' => $dataArray['Category - Main']]); 
					$Cat = $Cats->first();	 

					$exists_cats_subs = $Categories->exists(['name' => $dataArray['Category - Sub']]);
								if($exists_cats_subs){
									$Cats_subs = $Categories->find()->where(['name' => $dataArray['Category - Sub']]); 
									$Cat_sub = $Cats_subs->first();
									
									//pRODUCT cATEGORY tABLE
									$this->request->data['products_category']['category_id'] = $Cat_sub->id; 
								}else{	
									$error .= ' Record no.'. $key .': Sub Category "'. $dataArray['Category - Sub']  .'" not found.</br>'; 
								}
				}else{	
					$error .= ' Record no.'. $key .': Category "'. $dataArray['Category - Main']  .'" not found.</br>'; 
				}
				
				$exists_brands = $Brands->exists(['name' => $dataArray['Brand Name']]);
				if($exists_brands){
					$Brand = $Brands->find()->where(['name' => $dataArray['Brand Name']]); 
					$branid = $Brand->first();
					//pRODUCT Attr tABLE:: brand id 
					$this->request->data['products_attr']['brand_id'] = $branid->id;
				}else{ 
						$brandsArray = array();
						$brand = $Brands->newEntity();
						$brandsArray['name'] = $dataArray['Brand Name'];
						$brandsArray['image'] = $dataArray['Brand Logo Image'];
						$brandsArray['image_dir']= 'webroot\img\files\Brands\image\ ';
						$brandsArray['image_dir'] = trim($brandsArray['image_dir']);
						$brandsArray['templates']=1;
						$brandsArray['status']=1;
						
						$brand = $Brands->patchEntity($brand, $brandsArray);
						if ($Brands->save($brand)) {
							//pRODUCT Attr tABLE:: brand id 
								$this->request->data['products_attr']['brand_id'] = $brand->id;
							} else {
						$error .= ' Record no.'. $key .': Brand "'.$dataArray['Brand Name'].'" not added';
							}
				}
		
//Convert inputs to variables eNDS hERE

		if($exists_cats && $exists_cats_subs){



		$exists_item_code = $this->Products->exists(['item_code' => $dataArray['Item Code']]);	
		//if Product already exsist
		if($exists_item_code){	
		
					$pro_data = $this->Products->find()->where(['item_code' => $dataArray['Item Code']]); 
					$idByItemCode = $pro_data->first();	 
						$product = $this->Products->get($idByItemCode->id, [
						'contain' => ['ProductsCategories', 'ProductsAttrs','ProductsAttrs.Brands', 'ProductsImages','ProductsImages.Colors' , 'ProductsMarketingImages', 'ProductsPrices', 'ProductsRelateds', 'ProductsRelateds.relatedproduct1', 'ProductsRelateds.relatedproduct2', 'ProductsRelateds.relatedproduct3']
						]);
			
					$item_codes[]=$this->request->data['products_related']['relatedproduct1']['item_code'];		
					$item_codes[]=$this->request->data['products_related']['relatedproduct2']['item_code'];		
					$item_codes[]=$this->request->data['products_related']['relatedproduct3']['item_code'];		
		

								foreach($item_codes as $key1 => $item_code){
								$key1++;
								$query = $this->Products->find('all', ['conditions'=>['products.item_code'=>$item_code], 'fields'=>['id','item_code']]);
								$related_data = $query->toArray();
											if(count($related_data)>0){
											$this->request->data['products_related']['related_product_'.$key1]= $related_data[0]['id'];	
											}else{
											$this->request->data['products_related']['related_product_'.$key1]=0;
											}
								}
	
	
									$product = $this->Products->patchEntity($product, $this->request->data);
									
									
	if ($this->Products->save($product)) {
											
				
			////////////////////////////
			// Images Functionality
		    ////////////////////////////
							$marketingArray=array();	
							$id = $idByItemCode->id;
							$marketingimagePath= 'webroot\img\files\ProductsMarketingImages\image\ ';
							$marketingimagePath = trim($marketingimagePath); 
						
							$ProductsMarketingImages = TableRegistry::get('ProductsMarketingImages');
							$ProductsImages = TableRegistry::get('ProductsImages');								
				//Save Marketing images			
									$ProductsMarketingImages->deleteAll(['product_id' => $id]);
									if(!empty($this->request->data['products_marketing_image1'])){								
									$marketingArray['products_marketing_image'][1]['image']=$this->request->data['products_marketing_image1'];
									$marketingArray['products_marketing_image'][1]['product_id']= $id;
									$marketingArray['products_marketing_image'][1]['image_dir'] = $marketingimagePath;
															}
									if(!empty($this->request->data['products_marketing_image2'])){
									$marketingArray['products_marketing_image'][2]['image']=$this->request->data['products_marketing_image2'];
									$marketingArray['products_marketing_image'][2]['product_id']= $id;
									$marketingArray['products_marketing_image'][2]['image_dir'] = $marketingimagePath;
															}
									if(!empty($this->request->data['products_marketing_image3'])){
									$marketingArray['products_marketing_image'][3]['image']=$this->request->data['products_marketing_image3'];
									$marketingArray['products_marketing_image'][3]['product_id']= $id;
									$marketingArray['products_marketing_image'][3]['image_dir'] = $marketingimagePath;
															}
									if(!empty($this->request->data['products_marketing_image4'])){
									$marketingArray['products_marketing_image'][4]['image']=$this->request->data['products_marketing_image4'];
									$marketingArray['products_marketing_image'][4]['product_id']= $id;
									$marketingArray['products_marketing_image'][4]['image_dir'] = $marketingimagePath;
									}								
		
								foreach($marketingArray['products_marketing_image'] as $image_photo){
									$aa = $ProductsMarketingImages->newEntity();
									$aa = $ProductsMarketingImages->patchEntity($aa,$image_photo);
									$ProductsMarketingImages->save($aa);
								} 			
				//Save Colored images	
				$coloredImageArray['product_code']=$dataArray['Color Product Code'];
				$coloredImageArray['image']=$dataArray['Color Product Image'];
				$coloredImageArray['status']=$dataArray['Color Unavailable Flag'];
				$colorimagePath = 'webroot\img\files\ProductsImages\image\ ';
				$coloredImageArray['image_dir'] = trim($colorimagePath); 
				$coloredImageArray['product_id'] = $id;
				
				$exists_colors = $Colors->exists(['name' => $dataArray['Color Name']]);
				

				if($exists_colors){

					$color_data = $Colors->find()->where(['name' => $dataArray['Color Name']]); 
					$idByColorName = $color_data->first();	
					$coloredImageArray['color_id']=	$idByColorName->id;

					$exists_product_code = $this->Products->ProductsImages->exists(['product_code' => $coloredImageArray['product_code']]);	
					if($exists_product_code){	
						$pro_code = $this->Products->ProductsImages->find()->where(['product_code' => $coloredImageArray['product_code']]); 
						$idByProCode = $pro_code->first();	 
						$productImage = $this->Products->ProductsImages->get($idByProCode->id);
						$productImage = $this->Products->ProductsImages->patchEntity($productImage,$coloredImageArray);
						$ProductsImages->save($productImage);	
					}else{
						$ab = $ProductsImages->newEntity();
						$ab = $ProductsImages->patchEntity($ab,$coloredImageArray);
						$ProductsImages->save($ab);			
					}
					
				}	
				else{		
				$error .= ' Record no.'. $key .': Color "'.$dataArray['Color Name'].'" not found';	
				}
	
			$error .= ' Record no.'. $key .': Updated'; 
		} else {
			$error .= ' Record no.'. $key .': Not Updated'; 
		}
//////////////////////////////////////////	
//Edit existing Product function Ends here
//////////////////////////////////////////
				
				
				
				
//////////////////////////////////////////			
//if Product doesn`t exsist			
//////////////////////////////////////////	
		}
		else{
			

				$product = $this->Products->newEntity();

				$item_codes[]=$this->request->data['products_related']['relatedproduct1']['item_code'];		
					$item_codes[]=$this->request->data['products_related']['relatedproduct2']['item_code'];		
					$item_codes[]=$this->request->data['products_related']['relatedproduct3']['item_code'];		
		

								foreach($item_codes as $key1 => $item_code){
								$key1++;
								$query = $this->Products->find('all', ['conditions'=>['products.item_code'=>$item_code], 'fields'=>['id','item_code']]);
								$related_data = $query->toArray();
											if(count($related_data)>0){
											$this->request->data['products_related']['related_product_'.$key1]= $related_data[0]['id'];	
											}else{
											$this->request->data['products_related']['related_product_'.$key1]=0;
											}
								}

				$product = $this->Products->patchEntity($product, $this->request->data,[
				'associated' => ['ProductsAttrs', 'Categories', 'ProductsAttrs', 'ProductsPrices','ProductsRelateds', 'ProductsCategories']
				]);	

				$result = $this->Products->save($product);
				if ($result){ 
							
				  
////////////////////////////
// Images Functionality starts here
////////////////////////////
							$marketingArray=array();	
							$id = $result->id;
							$marketingimagePath= 'webroot\img\files\ProductsMarketingImages\image\ ';
							$marketingimagePath = trim($marketingimagePath); 
						
							$ProductsMarketingImages = TableRegistry::get('ProductsMarketingImages');
							$ProductsImages = TableRegistry::get('ProductsImages');								
				
///////////////////////
//Save Marketing images			
////////////////////////
									if(!empty($this->request->data['products_marketing_image1'])){								
									$marketingArray['products_marketing_image'][1]['image']=$this->request->data['products_marketing_image1'];
									$marketingArray['products_marketing_image'][1]['product_id']= $id;
									$marketingArray['products_marketing_image'][1]['image_dir'] = $marketingimagePath;
															}
									if(!empty($this->request->data['products_marketing_image2'])){
									$marketingArray['products_marketing_image'][2]['image']=$this->request->data['products_marketing_image2'];
									$marketingArray['products_marketing_image'][2]['product_id']= $id;
									$marketingArray['products_marketing_image'][2]['image_dir'] = $marketingimagePath;
															}
									if(!empty($this->request->data['products_marketing_image3'])){
									$marketingArray['products_marketing_image'][3]['image']=$this->request->data['products_marketing_image3'];
									$marketingArray['products_marketing_image'][3]['product_id']= $id;
									$marketingArray['products_marketing_image'][3]['image_dir'] = $marketingimagePath;
															}
									if(!empty($this->request->data['products_marketing_image4'])){
									$marketingArray['products_marketing_image'][4]['image']=$this->request->data['products_marketing_image4'];
									$marketingArray['products_marketing_image'][4]['product_id']= $id;
									$marketingArray['products_marketing_image'][4]['image_dir'] = $marketingimagePath;
									}								
		
								foreach($marketingArray['products_marketing_image'] as $image_photo){
									$aa = $ProductsMarketingImages->newEntity();
									$aa = $ProductsMarketingImages->patchEntity($aa,$image_photo);
									$ProductsMarketingImages->save($aa);
								}
/////////////////////							 
//Save Colored images	
/////////////////////
				$coloredImageArray['product_code']=$dataArray['Color Product Code'];
				$coloredImageArray['image']=$dataArray['Color Product Image'];
				$coloredImageArray['status']=$dataArray['Color Unavailable Flag'];
				$colorimagePath = 'webroot\img\files\ProductsImages\image\ ';
				$coloredImageArray['image_dir'] = trim($colorimagePath); 
				$coloredImageArray['product_id'] = $id;
				
				$exists_colors = $Colors->exists(['name' => $dataArray['Color Name']]);
				if($exists_colors){

					$color_data = $Colors->find()->where(['name' => $dataArray['Color Name']]); 
					$idByColorName = $color_data->first();	
					$coloredImageArray['color_id']=	$idByColorName->id;
					
					$exists_product_code = $this->Products->ProductsImages->exists(['product_code' => $coloredImageArray['product_code']]);	
					if($exists_product_code){	
						$pro_code = $this->Products->ProductsImages->find()->where(['product_code' => $coloredImageArray['product_code']]); 
						$idByProCode = $pro_code->first();	 
						$productImage = $this->Products->ProductsImages->get($idByProCode->id);
						$productImage = $this->Products->ProductsImages->patchEntity($productImage,$coloredImageArray);
						$ProductsImages->save($productImage);	
					}else{
						$ab = $ProductsImages->newEntity();
						$ab = $ProductsImages->patchEntity($ab,$coloredImageArray);
						$ProductsImages->save($ab);			
					}	
				}	
				else{	
					$error .= '  Record no.'. $key .': Color "'.$dataArray['Color Name'].'" not found';		
				}
					
					
	
			$error .= ' Record no.'. $key .': Added'; 
		} else {
			$error .= ' Record no.'. $key .': Not Added'; 
		}

			  
	} 
   }
   
   $error .= '</br>';
 }
	$logArray=array();
	$logArray['errors']=$error;
	$logArray['modified_by']= $this->Auth->User('id');
	$Log = $Logs->get(1);
	$Log = $Logs->patchEntity($Log, $logArray); 
	$Logs->save($Log);	
	return $this->redirect(['controller'=>'Logs', 'action' => 'index']);
}
  

}

    /**
     * Edit method
     *
     * @param string|null $id Product id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {	
		$Brands = TableRegistry::get('Brands');	
		$Categories = TableRegistry::get('Categories');	
		
		
        $product = $this->Products->get($id, [
            'contain' => ['ProductsCategories', 'ProductsAttrs','ProductsAttrs.Brands', 'ProductsImages','ProductsImages.Colors' , 'ProductsMarketingImages', 'ProductsPrices', 'ProductsRelateds', 'ProductsRelateds.relatedproduct1', 'ProductsRelateds.relatedproduct2', 'ProductsRelateds.relatedproduct3', 'Promotions']
        ]);
		
		
        if ($this->request->is(['patch', 'post', 'put'])) {
	
	$item_codes[]=$this->request->data['products_related']['relatedproduct1']['item_code'];		
	$item_codes[]=$this->request->data['products_related']['relatedproduct2']['item_code'];		
	$item_codes[]=$this->request->data['products_related']['relatedproduct3']['item_code'];		
	

	foreach($item_codes as $key => $item_code){
	$key++;
	$query = $this->Products->find('all', ['conditions'=>['products.item_code'=>$item_code], 'fields'=>['id','item_code']]);
	$related_data = $query->toArray();
				if(count($related_data)>0){
				$this->request->data['products_related']['related_product_'.$key]= $related_data[0]['id'];	
				}else{
				$this->request->data['products_related']['related_product_'.$key]=0;
				}
	}
	
	
	 			
        $product = $this->Products->patchEntity($product, $this->request->data);
            if ($this->Products->save($product)) {
                $this->Flash->success(__('The product has been saved.'));
            } else {
                $this->Flash->error(__('The product could not be saved. Please, try again.'));
            }
		return $this->redirect( Router::url( $this->referer(), true ) );	
        }
		
		$Configrations = TableRegistry::get('Configrations');
		$configs = $Configrations->find();
		$config =  $configs->first();
		$main_promos[0]='';
		$main_promos[$config->promo_page_1]=$config->promo_page_1;
		$main_promos[$config->promo_page_2]=$config->promo_page_2;
		$main_promos[$config->promo_page_3]=$config->promo_page_3;
		
		
		
		
		
		$parentCategory = $this->Products->Categories->find('all')
		->where(['parent_id IS' =>NULL])
		->toArray();
		
		
		$categories=$this->Products->Categories->find('all')
		->contain('ChildCategories')
		->where(['parent_id IS' =>$parentCategory[0]->id])
		->toArray(); 
		
		$AllCategories=array();
		foreach($categories as $category){
			
			foreach($category['child_categories'] as $key =>$subCat){
					$AllCategories[$category['name']][$subCat['id']] = $subCat['name'];
				} 
		}
		
		
		
		
		
		
		
		
        $brands = $Brands->find('list', ['limit' => 200]);
		$colors = $this->Products->ProductsImages->Colors->find('list', ['limit' => 200]);
		$childproducts = $this->Products->find('list', ['limit' => 200]);
        $categories = $this->Products->Categories->find('list', ['limit' => 200]);
        $this->set(compact('product', 'categories', 'brands', 'colors', 'main_promos', 'childproducts', 'AllCategories'));
        $this->set('_serialize', ['product']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Product id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $product = $this->Products->get($id);
        if ($this->Products->delete($product)) {
            $this->Flash->success(__('The product has been deleted.'));
        } else {
            $this->Flash->error(__('The product could not be deleted. Please, try again.'));
        }
        return $this->redirect(['action' => 'index']);
    }
	public function changestatus()
		{ 
		
			$id = $this->request->data['id'];
			if($this->request->is(['post'])){
				
				$product=$this->Products->get($id);
				if($product){
					if($product->status=="Y"){
					$new_status="N";						
					}elseif($product->status=="N"){					
					$new_status="Y";
					}
				
				$product->status=$new_status; 
				$this->Products->save($product);
				echo 'success';
				die;	
				}
					echo 'error';
					die;
			}
			
    }

}
