<?php
namespace RemindCloud\Service;

use Zend_Json;

class TinyMce
{
    /**
     * D2Helper service.
     * @var RemindCloud\Service\Doctrine\Helper
     */
    protected $_d2helper;

    /**
     * Zend_Controller_Front
     * @var \Zend_Controller_Front
     */
    protected $_front;

    /**
     * Constructor.
     */
    public function __construct($d2helper, $front)
    {
        $this->_d2helper = $d2helper;
        $this->_front = $front;
    }

    /**
     * Gets a website TinyMCE formatted image list for use in TinyMCE editors.
     *
     * @param integer $companyId
     * @return string
     */
    public function getWebsiteImageList($websiteId)
    {
        $baseUri = $this->_front->getParam('tinyImageListBaseUri');
        $er = $this->_d2helper->getEntityManager()->getRepository('RemindCloud\Entity\File');

        $images = $er->findImagesByWebsite($websiteId);
        $output = array();
        foreach ($images as $image)
        {
            $output[] = array(
                $image->name,
                $image->getPath(true)
            );
        }
        return 'var tinyMCEImageList = ' . Zend_Json::encode($output) . ';';
    }

    /**
     * Gets a website TinyMCE formatted link list for use in TinyMCE editors.
     *
     * @param integer $companyId
     * @return string
     */
    public function getWebsiteLinkList($websiteId)
    {
        $baseUri = $this->_front->getParam('staticFileUri');
        $em = $this->_d2helper->getEntityManager();

        $blogs = $em->getRepository('RemindCloud\Entity\Website\Blog')->findBy(
                    array('website' => $websiteId),
                        array('name' => 'ASC')
        );
        foreach ($blogs as $c)
        {
            $output[] = array(
                'Blog: ' . $c->name,
                '/blog/' . $c->slug
            );
        }

        $collections = $em->getRepository('RemindCloud\Entity\Website\Collection')->findBy(
                          array('website' => $websiteId),
                              array('name' => 'ASC')
        );
        foreach ($collections as $c)
        {
            $output[] = array(
                'Collection: ' . $c->name,
                '/collection/' . $c->slug
            );
        }


        $files = $em->getRepository('RemindCloud\Entity\File')->getWebsiteFileLinkList($websiteId);

        foreach ($files as $file)
        {
            $output[] = array(
                'File:' . $file->name,
                $file->getPath(true)
            );
        }


        $forms = $em->getRepository('RemindCloud\Entity\Website\Form')->findBy(
                    array('website' => $websiteId),
                        array('name' => 'ASC')
        );

        foreach ($forms as $form)
        {
            $output[] = array(
                'Form:' . $form->name,
                '/form/' . $form->slug
            );
        }

        $pages = $em->getRepository('RemindCloud\Entity\Website\Page')->findBy(
                    array('website' => $websiteId),
                        array('title' => 'ASC')
        );
        foreach ($pages as $page)
        {
            $output[] = array(
                'Page:' . $page->title,
                '/' . $page->slug
            );
        }

        return 'var tinyMCELinkList = ' . Zend_Json::encode($output) . ';';
    }

    /**
     * Gets a company TinyMCE formatted image list for use in TinyMCE editors.
     *
     * @param integer $companyId
     * @return string
     */
    public function getCompanyImageList($companyId)
    {
        $baseUri = $this->_front->getParam('tinyImageListBaseUri');
        $er = $this->_d2helper->getEntityManager()->getRepository('RemindCloud\Entity\File');

        $images = $er->findImagesByCompany($companyId);
        $output = array();
        foreach ($images as $image)
        {
            $output[] = array(
                $image->name,
                $image->getPath(true)
            );
        }
        return 'var tinyMCEImageList = ' . Zend_Json::encode($output) . ';';
    }
}