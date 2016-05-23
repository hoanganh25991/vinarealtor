<?php
namespace BackEnd\Controller;

use BackEnd\Form\PostFilter;
use BackEnd\Form\PostForm;
use BackEnd\Model\District;
use BackEnd\Model\Post;
use BackEnd\Model\PostFeature;
use BackEnd\Model\Province;
use BackEnd\Model\Ward;
use Zend\Form\Element;
use Zend\Http\Request;
use Zend\ServiceManager\ServiceManager;
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
            $post = new Post();
            $postFilter = new PostFilter();
            //            $postForm->setInputFilter($post->getInputFilter());
            $postForm->setInputFilter($postFilter);
            $postForm->setData($postParams);
            //            $postFeatures = $postParams->get("postFeatures");
            //            $postFeaturesArrayObject = new ArrayObject();
            //            foreach($postFeatures as $key => $value){
            //                $postFeaturesArrayObject[$key] = $value;
            //            }
            //            $postForm->bind($postFeaturesArrayObject);
            $provinceId = $postParams->get("provinceid");
            $ditricts = District::where("provinceid", $provinceId)->get();
            $districtOptions = array();
            foreach($ditricts as $item){
                $row = array();
                $row["value"] = $item["districtid"];
                $row["label"] = $item["name"];
                $districtOptions[] = $row;
            }
            /** @var Element\Select $districtSelect */
            $districtSelect = $postForm->get("districtid");
            $districtSelect->setEmptyOption(array(
                "label" => "--chọn tỉnh thành phố--",
                "disabled" => true,
                "selected" => true
            ));
            $districtSelect->setValueOptions($districtOptions);


            $districtId = $postParams->get("districtid");
            $wards= Ward::where("districtid", $districtId)->get();
            $wardOptions = array();
            foreach($wards as $item){
                $row = array();
                $row["value"] = $item["wardid"];
                $row["label"] = $item["name"];
                $wardOptions[] = $row;
            }
            /** @var Element\Select $wardSelect */
            $wardSelect = $postForm->get("wardid");
            $wardSelect->setEmptyOption(array(
                "label" => "--chọn tỉnh phường xã--",
                "disabled" => true,
                "selected" => true
            ));
            $wardSelect->setValueOptions($wardOptions);

            if($postForm->isValid()){
                /**
                 * for debug purpose
                 * dump data
                 */
                $post->category_id = 5;
                $post->user_id = 1;
                $post->post_status_id = 1;

                $post->fill((array)$postParams);
                $post->save();
                return $this->redirect()->toUrl("/");
            }
        }
        /**
         * inject province
         *
         * inject district by AJAX
         * inject ward by AJAX
         *
         */
        $provinces = Province::all();
        $provinceOptions = array();
        foreach($provinces as $item){
            $row = array();
            $row["value"] = $item["provinceid"];
            $row["label"] = $item["name"];
            $provinceOptions[] = $row;
        }
        /** @var Element\Select $proviceSelect */
        $proviceSelect = $postForm->get("provinceid");
        $proviceSelect->setEmptyOption(array(
            "label" => "--chọn tỉnh thành phố--",
            "disabled" => true,
            "selected" => true
        ));
        $proviceSelect->setValueOptions($provinceOptions);

        /**
         * inject feature
         */
        $postFeatures = PostFeature::all();

        $view->setVariable("postFeatures", $postFeatures);

        $view->setVariable("postForm", $postForm);

        return $view;
    }


}