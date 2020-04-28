<?php


namespace App\Form;


use App\Entity\Film;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FilmType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, ['label'=>"Enter film title: "])
            ->add('duration', IntegerType::class, ['label'=>"Enter film duration: "])
            ->add('year', IntegerType::class, ['label'=>"Enter release year: "])
            ->add('genre', ChoiceType::class,
                        ['choices'=>$options['genre_options'], 'mapped'=>false, 'label'=>"Choose film genre: "])
            ->add('cover', FileType::class, ['label'=>"Upload film cover: "])
            ->add('save', SubmitType::class, ['label'=>"Add film"]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Film::class,
            'genre_options'=> array()
        ]);

        $resolver->setAllowedTypes('genre_options', 'array');
    }
}