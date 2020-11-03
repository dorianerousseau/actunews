<?php


namespace App\Controller;


use App\Entity\Category;
use App\Entity\Post;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    # page / action : Accueil
    public function index()
    {
        # récupérer les 6 derniers articles de la BDD par ordre décroissant
        /* créer une variable = getDoctrine, le fichier qui va récup en bdd
         * ->getRepository(xxx::class) : l'entité que je souhaite récupérer les données.
         * ->findBy() : recup les données selon +ieurs critères
         * ->findOneBy() : recup un enregistrement selon +ieurs critéres
         * ->findAll() : recup toutes les données de la table
         * ->find(id) : recup une donnée via son ID
         */

        $posts = $this->getDoctrine()
            ->getRepository(Post::class)
            ->findBy([], ['id' => 'DESC'], 6);


        # pour transmettre à la vue
        return $this->render('default/index.html.twig',[
            'posts' => $posts
        ]);
    }

    # page : Contact
    public function contact()
    {
        return $this->render('default/contact.html.twig');
    }

    # page : Categorie
    /** permet d'afficher les articles d'une catégorie
     * sans passer par route.yalm
     * @Route("/{alias}", name="default_category", methods={"GET"})
     * {} = pour dire que c'est un paramètre, on l'appelle en variable $nomdelavar
     * pour le name "controller_action"
     * methods GET = uniquement la methode qui est autorisé
     */
    public function category($alias)
    {
        # Récupération de la catégorie via son alias dans l'URL
        $category = $this->getDoctrine()
            ->getRepository(Category::class)
            ->findOneBy(['alias' => $alias]);

        /*
         * Grace à la relation entre Post et Category (OneToMany),
         * je suis en mesure de récupérer les articles de la categorie.
         */
        $posts = $category->getPosts();

        return $this->render('default/category.html.twig',[
            'posts' => $posts
        ]);
    }

    # page : article
    /** Permet d'afficher un article du site
     * @Route("/{category}/{alias}_{id}.html", name="default_article", methods={"GET"})
     *
     */
    public function post($id)
    {
        # Récupérer l'article via son ID
        $post = $this->getDoctrine()
            ->getRepository(Post::class)
            ->find($id);

        # URL  http://localhost:8000/politique/couvre-feu-quand-la-situation-sanitaire-s-ameliorera-t-elle_14155614.html
        return $this->render('default/post.html.twig',[
            'post' => $post
        ]);
    }


}

