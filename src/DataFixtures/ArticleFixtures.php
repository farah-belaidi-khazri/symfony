<?php

namespace App\DataFixtures;

use App\Entity\Article;
use App\Entity\Category;
use App\Entity\Comment;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class ArticleFixtures extends Fixture
{
  public function load(ObjectManager $manager): void
      

    {  $faker = Factory ::create('en');
      for($i=1;$i<5;$i++){
          $category = new Category();
          $category->setTitle("category $i");
          $category->setDescription("category description $i");
          

          $manager->persist($category);

          
          for($j=1;$j<=2;$j++){
            $article = new Article();
            $article->setTitle("title $j")

                    ->setContent("VADER Sentiment Analysis :
                    VADER (Valence Aware Dictionary and sEntiment Reasoner) is a lexicon and rule-based sentiment analysis tool that is specifically attuned to sentiments expressed in social media. VADER uses a combination of A sentiment lexicon is a list of lexical features (e.g., words) which are generally labeled according to their semantic orientation as either positive or negative. VADER not only tells about the Positivity and Negativity score but also tells us about how positive or negative a sentiment is.")
                    ->setCreatedAt($faker->dateTimeBetween('-6 months'))
                    ->setImage("https://picsum.photos/id/6/300/150")
                    ->setCategory($category);
            $manager->persist( $article);
             $today = new DateTime();
             $diffrence = $today -> diff($article->getCreatedAt());
             $days = $diffrence->days;
             $comment_maximum = '-'. $days. 'days';
             for($k=0;$k<=mt_rand(4,6);$k++){

              $comment = new Comment();

              $comment ->setAuthor($faker->name)
                       ->setContent($faker->text)
                       ->setCreatedAt($faker->dateTimeBetween( $comment_maximum))
                       ->setArticle($article);

              $manager->persist($comment);

          }

        }

        $manager->flush();
    }
}
}