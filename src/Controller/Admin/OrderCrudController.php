<?php

namespace App\Controller\Admin;

use App\Entity\Order;
use App\Service\MailJet;
use Doctrine\ORM\EntityManagerInterface;
use App\Controller\Admin\OrderCrudController;
use Symfony\Component\HttpFoundation\Request;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Router\CrudUrlGenerator;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;


class OrderCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Order::class;
    }

    public function configureActions(Actions $actions): Actions
    {
        $updateCommandPreparation = Action::new('updateCommandPreparation', "Préparation en cours", "fas fa-box-open text-info")->linkToCrudAction("updateCommandPreparation");
        $updateCommandDelivery = Action::new('updateCommandDelivery', "Livraison en cours", "fas fa-truck text-primary")->linkToCrudAction("updateCommandDelivery");
        $updateCommandDelivered = Action::new('updateCommandDelivered', "Commande livré", "fas fa-check text-success")->linkToCrudAction("updateCommandDelivered");
        return $actions
                    ->add('index', 'detail')
                    ->add('detail', $updateCommandPreparation)
                    ->add('detail', $updateCommandDelivery)
                    ->add('detail', $updateCommandDelivered)
                    ->remove('index', 'edit')
        ;            
    }

    public function updateCommandPreparation(AdminContext $context, EntityManagerInterface $em, Request $request)
    {
        $order = $context->getEntity()->getInstance();
        if($order->getState() != 2){
                $order->setState(2);
                $em->flush(); 
                $this->addFlash("notice", "<span style='color: green;'><strong>La commande ". $order->getReference() ." est en cours de préparation.</strong></span>");
        }
        // $url = $urlGenerator->build()
        //                     ->setController(OrderCrudController::class)
        //                     ->setAction('index')
        //                     ->generateUrl()
        // ;

        $email = new MailJet();
        $content = "Bonjour ". $order->getUser()->getFirstName().",<br> <p>Votre commande Ez-CBD, ". $order->getReference() ." est en cours de préparation <hr>  Lorem ipsum dolor sit amet, consectetur adipisicing elit. Eaque voluptate nulla ipsa aperiam atque minima deleniti corporis tempora esse explicabo accusamus aliquid est ullam quae neque, facere reiciendis quia quidem.</p>";
        $email->send($order->getUser()->getEmail(),$order->getUser()->getFirstName(), " Votre commande Ez-CBD est bien validée", $content);

        $referer = $request->headers->get('referer');
        return $this->redirect($referer);
        
    }

    public function updateCommandDelivery(AdminContext $context, EntityManagerInterface $em, Request $request)
    {
        $order = $context->getEntity()->getInstance();
        if($order->getState() != 3){
                $order->setState(3);
                $em->flush(); 
                $this->addFlash("notice", "<span style='color: green;'><strong>La commande ". $order->getReference() ." est en cours de livraison.</strong></span>");

            $email = new MailJet();
            $content = "Bonjour ". $order->getUser()->getFirstName().",<br> <p>Votre commande Ez-CBD, ". $order->getReference() ." est en cours de livraison <hr>  Lorem ipsum dolor sit amet, consectetur adipisicing elit. Eaque voluptate nulla ipsa aperiam atque minima deleniti corporis tempora esse explicabo accusamus aliquid est ullam quae neque, facere reiciendis quia quidem.</p>";
            $email->send($order->getUser()->getEmail(),$order->getUser()->getFirstName(), " Votre commande Ez-CBD est bien validée", $content);
        }

        $referer = $request->headers->get('referer');
        return $this->redirect($referer);
        
    }

    public function updateCommandDelivered(AdminContext $context, EntityManagerInterface $em, Request $request)
    {
        $order = $context->getEntity()->getInstance();
        if($order->getState() != 4){
                $order->setState(4);
                $em->flush(); 
                $this->addFlash("notice", "<span style='color: green;'><strong>La commande ". $order->getReference() ." a été livré.</strong></span>");

            $email = new MailJet();
            $content = "Bonjour ". $order->getUser()->getFirstName().",<br> <p>Votre commande Ez-CBD, ". $order->getReference() ." a été livrée <hr>  Lorem ipsum dolor sit amet, consectetur adipisicing elit. Eaque voluptate nulla ipsa aperiam atque minima deleniti corporis tempora esse explicabo accusamus aliquid est ullam quae neque, facere reiciendis quia quidem.</p>";
            $email->send($order->getUser()->getEmail(),$order->getUser()->getFirstName(), " Votre commande Ez-CBD est bien validée", $content);
        }

        $referer = $request->headers->get('referer');
        return $this->redirect($referer);
        
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud->setDefaultSort(['id' => 'DESC']);
    }   


    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            DateTimeField::new('createdAt', 'Commande passée le'),
            TextField::new('user.getFullName', "Client"),
            MoneyField::new('total', 'Prix total')->setCurrency('EUR'),
            TextField::new('carrierName', "Transporteur"),
            MoneyField::new('carrierPrice', 'Frais de port')->setCurrency('EUR'),
            ChoiceField::new('state', 'Statut commande')->setChoices([
                'Non payée' => 0,
                'Paiement validé' => 1,
                'Préparation en cours' => 2,
                'Livraison en cours' => 3,
                'Commande livrée' => 4
            ]),
            ArrayField::new('orderDetails', 'Produits achetés')->hideOnIndex()
    
        ];
    }
   
}
