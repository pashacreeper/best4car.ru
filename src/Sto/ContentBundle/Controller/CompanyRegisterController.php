<?php
namespace Sto\ContentBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sto\ContentBundle\Form\Extension\ChoiceList\CompanyRegistrationStep;
use Sto\ContentBundle\Form\RegistrationType;
use Sto\ContentBundle\Form\Type\ComapnyGalleryType;
use Sto\ContentBundle\Form\Type\CompanyBaseType;
use Sto\ContentBundle\Form\Type\CompanyBusinessProfileType;
use Sto\ContentBundle\Form\Type\CompanyContactsType;
use Sto\CoreBundle\Entity\Company;
use Sto\CoreBundle\Entity\CompanyAutoService;
use Sto\CoreBundle\Entity\CompanyManager;
use Sto\UserBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Sto\CoreBundle\Entity\CompanyWorkingTime;
use Sto\CoreBundle\Entity\CompanySpecialization;

class CompanyRegisterController extends Controller
{
    /**
     * Registration company owner
     *
     * @Route("/registration/company", name="registration_company_owner")
     * @Template()
     */
    public function newCompanyOwnerAction()
    {
        $user = new User();
        $form = $this->createForm(new RegistrationType('Sto\UserBundle\Entity\User'), $user);

        return [
            'user' => $user,
            'form' => $form->createView(),
        ];
    }

    /**
     * Creates a new User entity.
     *
     * @Route("/create-company-owner", name="registration_company_owner_create")
     * @Method("POST")
     * @Template("StoContentBundle:User:newCompanyOwner.html.twig")
     */
    public function createCompanyOwnerAction(Request $request)
    {
        $user  = new User();
        $form = $this->createForm(new RegistrationType('Sto\UserBundle\Entity\User'), $user);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $ratingGroup = $em->getRepository('StoUserBundle:RatingGroup')->find(1);
            $user->setRatingGroup($ratingGroup);
            $group = $em->getRepository('StoUserBundle:Group')->find(4);
            $user->setGroups([$group]);
            $user->setEnabled(true);

            $another_user = $em->getRepository('StoUserBundle:User')->findBy(['username'=>$user->getUsername()]);
            $another_email = $em->getRepository('StoUserBundle:User')->findBy(['email'=>$user->getEmail()]);
            if ($another_user || $another_email) {
                if ($another_user) {
                    $form->get('username')->addError(new FormError('Пользователь с таким ником уже зарегистрирован!'));
                }
                if ($another_email) {
                    $form->get('email')->addError(new FormError('Пользователь с таким почтовым адресом уже зарегистрирован!'));
                }

                return [
                    'user' => $user,
                    'form' => $form->createView(),
                ];
            }

            $em->persist($user);
            $em->flush();

            $this->get('sto.user.authenticate')->authenticate($user);

            return $this->redirect($this->generateUrl('registration_company_base'));
        }

        return [
            'user' => $user,
            'form' => $form->createView(),
        ];
    }

    /**
     * @Route("/new-company/base", name="registration_company_base")
     * @Route("/new-company/{id}/base", name="registration_company_base_with_id")
     * @Template()
     */
    public function baseAction(Request $request, $id = null)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        if (! $id || ! $company = $em->getRepository('StoCoreBundle:Company')->find($id)) {
            $company = new Company();
        }

        if ($company->isRegistredFully()) {
            throw new AccessDeniedException('Данная компания уже зарегистрирована');
        }

        $form = $this->createForm(new CompanyBaseType(), $company);

        if ('POST' === $request->getMethod()) {
            $form->bind($request);

            if ($form->isValid()) {
                $manager = new CompanyManager();
                $manager->setUser($user);
                $manager->setPhone($user->getPhoneNumber());
                $manager->setCompany($company);

                $company->addCompanyManager($manager);
                $company->setRegistredFully(false);
                $company->setRegistrationStep(CompanyRegistrationStep::BUSINESS);
                $company->setVisible(false);

                $em->persist($company);
                $em->flush();

                return $this->redirect($this->generateUrl('registration_company_business_profile', [
                    'id' => $company->getId()
                ]));
            }
        }

        return [
            'form' => $form->createView()
        ];
    }

    /**
     * @Route("/new-company/{id}/business-profile/", name="registration_company_business_profile")
     * @Template()
     */
    public function businessProfileAction(Request $request, Company $company)
    {
        if ($company->isRegistredFully()) {
            throw new AccessDeniedException('Данная компания уже зарегистрирована');
        }

        if ($company->getSpecializations()->count() === 0) {
            $company->addSpecialization(new CompanySpecialization());
        }

        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(new CompanyBusinessProfileType(), $company);

        $additionalServiceTypes = $em->getRepository('StoCoreBundle:Dictionary\AdditionalService')->findAll();

        if ('POST' === $request->getMethod()) {
            $form->bind($request);

            if ($form->isValid()) {
                $company->setRegistrationStep(CompanyRegistrationStep::CONTACTS);
                $em->persist($company);

                $services = $request->get('services');
                foreach ($company->getSpecializations() as $key => $item) {
                    if (isset($services[$key])) {
                        $itemServices = $services[$key];
                        foreach ($item->getServices() as $oldService) {
                            $serviceId = $oldService->getService()->getId();
                            if (($serviceKey = array_search($serviceId, $itemServices)) !== false) {
                                unset($itemServices[$serviceKey]);
                            }
                        }
                        $companyServices = [];
                        foreach ($itemServices as $serviceId) {
                            $autoService = $em->getRepository('StoCoreBundle:AutoServices')->find($serviceId);
                            $service = new CompanyAutoService();
                            $service->setService($autoService);
                            $service->setSpecialization($item);
                            $companyServices[] = $service;
                            $em->persist($service);
                        }
                        foreach ($item->getServices() as $service) {
                            $companyServices[] = $service;
                        }
                        foreach ($companyServices as $companyService) {
                            if ($companyService->getService()->getParent()) {
                                $this->createCompanyServiceParent($companyServices, $companyService, $item);
                            }
                        }
                        $em->flush();
                        foreach ($companyServices as $companyService) {
                            if ($companyService->getService()->getParent()) {
                                $this->setCompanyServiceParent($companyServices, $companyService);
                            }
                        }
                    }
                }

                $em->flush();

                return $this->redirect($this->generateUrl('registration_company_contacts', [
                    'id' => $company->getId()
                ]));
            }
        }

        return [
            'company' => $company,
            'form' => $form->createView(),
            'additionalServiceTypes' => $additionalServiceTypes
        ];
    }

    /**
     * @Route("/new-company/{id}/contacts", name="registration_company_contacts")
     * @Template()
     */
    public function contactsAction(Request $request, Company $company)
    {
        if ($company->isRegistredFully()) {
            throw new AccessDeniedException('Данная компания уже зарегистрирована');
        }

        if ($company->getWorkingTime()->count() === 0) {
            $company->addWorkingTime(new CompanyWorkingTime());
        }
        if (!$company->getPhones()) {
            $company->setPhones([['phone' => '', 'description' => '']]);
        }

        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(new CompanyContactsType(),
            $company,
            ['em' => $em]
        );

        if ('POST' === $request->getMethod()) {
            $form->bind($request);

            if ($form->isValid()) {
                $company->setRegistrationStep(CompanyRegistrationStep::GALLERY);
                $company->setRegistredFully(true);
                $company->setVisible(true);
                $em->persist($company);
                $em->flush();

                return $this->redirect($this->generateUrl('registration_company_gallery', [
                    'id' => $company->getId()
                ]));
            }
        }

        return [
            'form' => $form->createView(),
            'company' => $company
        ];
    }

    /**
     * @Route("/new-company/{id}/gallery", name="registration_company_gallery")
     * @Template()
     */
    public function galleryAction(Request $request, Company $company)
    {
        if ($company->isRegistredFully() && $company->getRegistrationStep() === null) {
            throw new AccessDeniedException('Данная компания уже зарегистрирована');
        }

        $form = $this->createForm(new ComapnyGalleryType(), $company);
        $em = $this->getDoctrine()->getManager();

        if ('POST' === $request->getMethod()) {
            $form->bind($request);

            if ($form->isValid()) {
                $gallery = $company->getGallery();
                foreach ($gallery as $value) {
                    $value->setVisible(true);
                    $value->setCompany($company);
                }
                $company->setGallery($gallery);

                $company->setRegistrationStep(null);
                $em->persist($company);
                $em->flush();

                return $this->redirect($this->generateUrl('content_company_show', [
                    'id' => $company->getId()
                ]));
            }
        }

        return [
            'form' => $form->createView(),
            'company' => $company
        ];
    }

    protected function createCompanyServiceParent(&$companyServices, $companyService, $specialization)
    {
        $companyServiceParent = null;
        $service = $companyService->getService()->getParent();
        foreach ($companyServices as $seachCompanyService) {
            if ($service == $seachCompanyService->getService()) {
                $companyServiceParent = $seachCompanyService;
                break;
            }
        }
        if (!$companyServiceParent) {
            $companyServiceParent = new CompanyAutoService();
            $companyServiceParent->setService($service);
            $companyServiceParent->setSpecialization($specialization);
            $em = $this->getDoctrine()->getManager();
            $em->persist($companyServiceParent);
            $em->flush();
            $companyServices[] = $companyServiceParent;
        }
        if ($service->getParent()) {
            $this->createCompanyServiceParent($companyServices, $companyServiceParent, $specialization);
        }
    }

    protected function setCompanyServiceParent($companyServices, $companyService)
    {
        foreach ($companyServices as $seachCompanyService) {
            if ($companyService->getService()->getParent() == $seachCompanyService->getService()) {
                $companyService->setParent($seachCompanyService);
                break;
            }
        }
    }
}