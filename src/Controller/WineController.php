<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class WineController extends AbstractController
{
    #[Route('/wine', name: 'app_wine')]
    public function index()
    {
    }
}
