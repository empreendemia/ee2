<?php

class Api_CompanyController extends Zend_Controller_Action
{
    private function parseCompany(company){
        if ($company) {
            /* Cria variável limpa */
            $company_new = null;
            
            /* Slug */
            $company_new->slug = $company->slug;
            
            /* Name */
            $company_new->name = $company->name;
            
            /* Thumbnail */
            $company_new->thumbnail = null;
            $company_new->thumbnail->small->url = 'http://www.empreendemia.com.br/'.$company->imagePath(50);
            $company_new->thumbnail->small->bucket = null;
            $company_new->thumbnail->small->folder = null;
            $company_new->thumbnail->small->filename = $company->image;
            $company_new->thumbnail->small->extension = null;
            $company_new->thumbnail->small->width = 50;
            $company_new->thumbnail->small->height = 50;
            $company_new->thumbnail->small->title = "Thumbnail";
            $company_new->thumbnail->small->legend = null;
            $company_new->thumbnail->medium->url = 'http://www.empreendemia.com.br/'.$company->imagePath(100);
            $company_new->thumbnail->medium->bucket = null;
            $company_new->thumbnail->medium->folder = null;
            $company_new->thumbnail->medium->filename = $company->image;
            $company_new->thumbnail->medium->extension = null;
            $company_new->thumbnail->medium->width = 100;
            $company_new->thumbnail->medium->height = 100;
            $company_new->thumbnail->medium->title = "Thumbnail";
            $company_new->thumbnail->medium->legend = null;
            $company_new->thumbnail->large->url = 'http://www.empreendemia.com.br/'.$company->imagePath(200);
            $company_new->thumbnail->large->bucket = null;
            $company_new->thumbnail->large->folder = null;
            $company_new->thumbnail->large->filename = $company->image;
            $company_new->thumbnail->large->extension = null;
            $company_new->thumbnail->large->width = 200;
            $company_new->thumbnail->large->height = 200;
            $company_new->thumbnail->large->title = "Thumbnail";
            $company_new->thumbnail->large->legend = null;
            unset($company->image);
            
            /* Sectors */
            unset($company->sector->id);
            $company->sector->parents = null;
            $company->sector->children = null;
            //$company_new->sectors = array();
            $company_new->sectors[] = $company->sector;
            unset($company->sector);
            
            /* Address */
            $company_new->address = null;
            $company_new->address->city = $company->city->name;
            $company_new->address->headQuarter = null;
            $company_new->addresses[] = $company_new->address;
            unset($company_new->address);
            
            /* Type */
            $company_new->type = $company->type;
            
            /* Profile */
            if ($company->profile == 'all')
                $company_new->profile = 'both';
            else
                $company_new->profile = $company->profile;
            
            /* Tags */
            $company_new->tags = array();
            
            /* Activity */
            $company_new->activity = $company->activity;
            
            /* Abstract */
            $company_new->abstract = $company->description;
            
            /* About */
            $company_new->about = $company->about;
            
            /* Links */
            $company_new->links = array();
            //Website
            $company_new->link->type = 'website';
            $company_new->link->url = $company->website;
            $company_new->links[0] = $company_new->link;
            unset($company_new->link);
            //Blog
            $company_new->link->type = 'blog';
            $company_new->link->url = $company->link_blog;
            $company_new->links[1] = $company_new->link;
            unset($company_new->link);
            //Vimeo
            $company_new->link->type = 'vimeo';
            $company_new->link->url = $company->link_vimeo;
            $company_new->links[2] = $company_new->link;
            unset($company_new->link);
            //YouTube
            $company_new->link->type = 'youtube';
            $company_new->link->url = $company->link_youtube;
            $company_new->links[3] = $company_new->link;
            unset($company_new->link);
            //SlideShare
            $company_new->link->type = 'slideshare';
            $company_new->link->url = $company->link_slideshare;
            $company_new->links[4] = $company_new->link;
            unset($company_new->link);
            //Facebook
            $company_new->link->type = 'facebook';
            $company_new->link->url = $company->link_facebook;
            $company_new->links[5] = $company_new->link;
            unset($company_new->link);
            
            /* Embeddeds */
            $company_new->link->type = 'slideshare';
            $company_new->link->url = $company->link_slideshare;
            $company_new->embedded->link = $company_new->link;
            $company_new->embedded->embed = $company->slides_embed;
            $company_new->embeddeds[] = $company_new->embedded;
            unset($company_new->embedded);
            unset($company_new->link);
            
            /* Date Created */
            $company_new->dateCreated = $company->date_created;
            
            /* Date Updated */
            $company_new->dateUpdate = $company->date_updated;

	    return $company_new;
        }

	return null;
    }

    public function viewAction(){     
        
        $this->getResponse()->setHeader("Access-Control-Allow-Origin","*",true);
        
        // pega parâmetro passado
        $company_id = $this->_getParam('slug');   
        
        // procura a empresa
        $company_mapper = new Ee_Model_Companies();
        $company = $this->parseCompany($company_mapper->find($company_id));
        
        if($company){
            $response->meta->type = 'Company';
            $response->response->status = 'success';
            $response->data = $company_new;
        }
        else {
            $response->response->status = 'error';
            $response->response->error = 'company "'.$company_id.'" does not exist'; 
            $response->data = null;
        }

        
        $json = Zend_Json::encode($response);
        $this->view->content = $json;
        $this->renderScript("response/ajax.phtml");
    }
    
    public function listAction(){     
        
        $this->getResponse()->setHeader("Access-Control-Allow-Origin","*",true); 
        
        // lista empresas
        $company_mapper = new Ee_Model_Companies();
        $companies = $company_mapper->directory();
	$companies_new = new Array();
        
        foreach ($companies as $company)
	    array_push($companies_new, $this->parseCompany($company));

        $response->meta->type = 'Company[]';
        $response->response->status = 'success';
        $response->data = $companies_new; 
        
        $json = Zend_Json::encode($response);
        $this->view->content = $json;
        $this->renderScript("response/ajax.phtml");
    }
}

