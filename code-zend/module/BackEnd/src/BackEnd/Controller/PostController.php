<?php
namespace BackEnd\Controller;

use BackEnd\Form\Element\DeepCheckbox;
use BackEnd\Form\PostFilter;
use BackEnd\Form\PostForm;
use BackEnd\Model\Category;
use BackEnd\Model\District;
use BackEnd\Model\Post;
use BackEnd\Model\PostFeature;
use BackEnd\Model\PostFeatureDetail;
use BackEnd\Model\PostImage;
use BackEnd\Model\PostTaxHistory;
use BackEnd\Model\Province;
use BackEnd\Model\Ward;
use BackEnd\UISupport\MenuHierarchy;
use Zend\Form\Element;
use Zend\Http\Request;
use Zend\ServiceManager\ServiceManager;
use Zend\Validator;
use Zend\View\Model\ViewModel;

/**
 * @warn
 * 1. checkbox can not save state with PostForm
 * (bcs it not a part of PostForm,
 * dynamicly created by postFeatures)
 */

/**
 * Class PostController
 * @package BackEnd\Controller
 */
class PostController extends DatabaseController{
    protected $serviceManager;

    private $tmp;

    /**
     * PostController constructor.
     * @param ServiceManager $sm
     */
    public function __construct($sm){
        parent::__construct($sm);
    }

    public function indexAction(){
        $new = new ViewModel();
        return $new;
    }

    /**
     * @return ViewModel
     */
    public function createAction(){
        $view = new ViewModel();
        //        $postFilter = new PostFilter();
        $postForm = new PostForm("post_form");
        //        $postForm->setInputFilter($postFilter);
        /** @var Request $request */
        $request = $this->getRequest();

        if($request->isGet()){

        }

        if($request->isPost()){
            $postParams = $request->getPost();
            var_dump($postParams);
//            return new JsonModel(array("name" => "anh"));
//            return new JsonModel($postParams);

            $post = new Post();

            $postFilter = new PostFilter();

            $postForm->setInputFilter($postFilter);
            $postForm->setData($postParams);

            /**
             * check valid for dynamic input
             */
            $isDigitValid = true;
            $digitValidMsg = "";

            $digitValidator = new  Validator\Digits();
            $digitValidator->setMessage("bạn phải nhập số tiền", Validator\Digits::STRING_EMPTY);
            $digitValidator->setMessage("chỉ nhập số, đơn vị tính VND", Validator\Digits::NOT_DIGITS);

            $taxHistory = $postParams->get("taxHistory");
            //check bcs, user may not input anything >>> taxHistory not exist
            //$taxHistory now handle null value

            if(is_array($taxHistory)){
                foreach($taxHistory as $record){
                    if(!$digitValidator->isValid($record[0]) || !$digitValidator->isValid($record[1])){
                        $isDigitValid = false;
                        //only get the first one
                        $digitValidMsg = array_values($digitValidator->getMessages())[0];
                    }
                }
            }

            $view->setVariable("digitValidMsg", $digitValidMsg);

            /**
             * check valid of form
             */
            if($postForm->isValid() && $isDigitValid){
                /**
                 * for debug purpose
                 * dump data
                 */
//                $post->category_id = 5;
                $post->user_id = 1;
                $post->post_status_id = 1;

                $post->fill((array)$postParams);
                $post->save();

                /**
                 * after success save post (basic info)
                 * handle map post-feature
                 * handle map post-tax
                 */
                //map post-feaure
                $deepFeatures = $postParams->get("deepFeature");
                if(is_array($deepFeatures)){
                    foreach($deepFeatures as $deepFeatureId){
                        $postFeatureDetail = new PostFeatureDetail();
                        $postFeatureDetail->post_id = $post->id;
                        $postFeatureDetail->post_features_id = $deepFeatureId;
                        $postFeatureDetail->save();
                    }
                }
                //map post-tax
                if(is_array($taxHistory)){
                    foreach($taxHistory as $record){
                        $postTaxHistory = new PostTaxHistory();
                        $postTaxHistory->post_id = $post->id;
                        $postTaxHistory->price = $record[0];
                        $postTaxHistory->year = $record[1];
                        $postTaxHistory->save();
                    }
                }

                $this->saveImage($post->user_id, $post->id);

                return $this->redirect()->toUrl("/");
//                return new JsonModel(array("post_id" => $post->id, "deepFeature" => $postParams->get("deepFeature"), "taxHistory" => $postParams->get("taxHistory")));
            }

            /**
             * innject options for district-select
             */
            $provinceId = $postParams->get("provinceid");
            $ditricts = District::where("provinceid", $provinceId)->select("districtid AS value", "name AS label")->get();
            /** @var Element\Select $districtSelect */
            $districtSelect = $postForm->get("districtid");
            $districtSelect->setValueOptions($ditricts->toArray());

            /**
             * inject options for ward-select
             */
            $districtId = $postParams->get("districtid");
            $wards = Ward::where("districtid", $districtId)->select("wardid AS value", "name AS label")->get();
            /** @var Element\Select $wardSelect */
            $wardSelect = $postForm->get("wardid");
            $wardSelect->setValueOptions($wards->toArray());
        }
        /**
         * inject options for province-select
         */

        $provinces = Province::select("provinceid AS value", "name AS label")->get();
        /** @var Element\Select $proviceSelect */
        $proviceSelect = $postForm->get("provinceid");
        $proviceSelect->setValueOptions($provinces->toArray());

        /**
         * inject checkbox for deep checkbox "feature"
         */
        $postFeatures = PostFeature::all();
        /** @var DeepCheckbox $deepFeature */
        $deepFeature = $postForm->get("deepFeature");
        /**
         * inject back checked feature (not a good solution),
         * when data injected by PostForm through "setData"
         */
        $deepFeature->setCheckboxes($postFeatures);

        /**
         * inject category select box hierarchy
         */
        $categories = Category::select("id AS value", "name AS label", "parent")->get();
        //reset before loop
//        $a = array();
//        $this->tmp = &$a;
//        MenuHierarchy::$tmp = array();
        $data  = MenuHierarchy::reArrange($categories->toArray(), 0, 1, false);
//        var_dump($this->tmp);
//        var_dump($a);
        /** @var Element\Select $categorySelect */
        $categorySelect = $postForm->get("category_id");
        $categorySelect->setValueOptions($data);

        $view->setVariable("postForm", $postForm);

//        $data = array(
//            array("id" => 1, "parent" => 0),
//            array("id" => 2, "parent" => 0),
//            array("id" => 3, "parent" => 0),
//            array("id" => 4, "parent" => 0),
//            array("id" => 5, "parent" => 1),
//            array("id" => 6, "parent" => 1),
//            array("id" => 7, "parent" => 2),
//            array("id" => 8, "parent" => 3),
//            array("id" => 9, "parent" => 4),
//            array("id" => 10, "parent" => 4),
//        );

//        MenuHierarchy::$tmp = array();
//        MenuHierarchy::reArrange($data, 0, 1);
//        $store = MenuHierarchy::$tmp;

        return $view;
    }

    private function saveImage($userId, $postId){
        $postImage = new PostImage();
        $postImageColumns = array(
            "name",
            "type",
            "size",
            "path",
            "post_id"
        );
        $postImageValues = array();
        /**
         * hanlde make directory
         * bcs $userId / $postId not exist
         * only data/images exist
         */
        $outputDir = "data/images/" . $userId . "/" . $postId . "/";
        if(!mkdir($outputDir, 0700)){
            mkdir($outputDir, 0700);
        }
        $inputFileName = "uploadImage";
        $ret = array();
        if(isset($_FILES[$inputFileName])){
            $files = $this->diverse_array($_FILES[$inputFileName]);
            foreach($files as $file){
                $fileName = $file["name"];
                move_uploaded_file($file["tmp_name"], $outputDir . $fileName);
                $postImageValues[] = $fileName;
                $postImageValues[] = $file["type"];
                $postImageValues[] = $file["size"];
                $postImageValues[] = $outputDir . $fileName;
                $postImageValues[] = $postId;
                $ret[] = $fileName;
                $a = array_combine($postImageColumns, $postImageValues);
                $postImage->fill(array_combine($postImageColumns, $postImageValues));
                $postImage->save();
            }
            //            if(!is_array($files["name"])){
            //                $fileName = $files["name"];
            //                move_uploaded_file($files["tmp_name"], $outputDir . $fileName);
            //                $postImageValues[] = $fileName;
            //                $postImageValues[] = $files["type"];
            //                $postImageValues[] = $files["size"];
            //                $postImageValues[] = $outputDir . $fileName;
            //                $postImageValues[] = $postId;
            //                $ret[] = $fileName;
            //                $postImage->insert($postImageColumns, $postImageValues);
            //            }else{
            //                $fileCount = count($files["name"]);
            //                for($i = 0; $i < $fileCount; $i++){
            //                    $fileName = $files["name"][$i];
            //                    move_uploaded_file($files["tmp_name"][$i], $outputDir . $fileName);
            //                    $postImageValues[] = $fileName;
            //                    $postImageValues[] = $files["type"][$i];
            //                    $postImageValues[] = $files["size"][$i];
            //                    $postImageValues[] = $outputDir . $fileName;
            //                    $postImageValues[] = $postId;
            //                    $postImage->insert($postImageColumns, $postImageValues);
            //                    $ret[] = $fileName;
            //                }
            //
            //            }
        }
        return $ret;
    }

    private function diverse_array($vector){
        $result = array();
        foreach($vector as $key1 => $value1){
            foreach($value1 as $key2 => $value2){
                $result[$key2][$key1] = $value2;
            }
        }
        return $result;
    }
}