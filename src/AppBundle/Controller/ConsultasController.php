<?php

namespace AppBundle\Controller;

use Doctrine\ORM\EntityManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ConsultasController extends Controller
{
    /**
     * @Route("/ej1", name="ejercicio1")
     */
    public function ej1Action()
    {
        $em = $this->getDoctrine()->getManager();
        $alumnado = $em->getRepository('AppBundle:Alumno')
            ->findBy(['nombre' => 'María']);

        return $this->render('consultas/alumnado.html.twig', [
            'alumnado' => $alumnado
        ]);
    }

    /**
     * @Route("/ej2", name="ejercicio2")
     */
    public function ej2Action()
    {
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();
        $alumnado = $em->createQuery(
            'SELECT a, g FROM AppBundle:Alumno a JOIN a.grupo g WHERE a.nombre != :nombre')
            ->setParameter('nombre', 'María')
            ->getResult();

        return $this->render('consultas/alumnado.html.twig', [
            'alumnado' => $alumnado
        ]);
    }

    /**
     * @Route("/ej3/{parametro}", name="ejercicio3")
     */
    public function ej3Action($parametro)
    {
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();
        $alumnado = $em->createQueryBuilder()
            ->select('a')       // ->select(['a', 'g']) también es equivalente
            ->addSelect('g')
            ->from('AppBundle:Alumno', 'a')
            ->join('a.grupo', 'g')
            ->where('a.nombre = :nombre')
            ->setParameter('nombre', $parametro)
            ->getQuery()
            ->getResult();

        return $this->render('consultas/alumnado.html.twig', [
            'alumnado' => $alumnado
        ]);
    }

    /**
     * @Route("/ej4", name="ejercicio4")
     */
    public function ej4Action()
    {
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();
        $alumnado = $em->createQueryBuilder()
            ->select('a')       // ->select(['a', 'g']) también es equivalente
            ->addSelect('g')
            ->from('AppBundle:Alumno', 'a')
            ->join('a.grupo', 'g')
            ->where('a.apellidos LIKE :apellido')
            ->setParameter('apellido', 'Ojeda %')
            ->orderBy('a.nombre')
            ->getQuery()
            ->getResult();

        return $this->render('consultas/alumnado.html.twig', [
            'alumnado' => $alumnado
        ]);
    }

    /**
     * @Route("/ej5", name="ejercicio5")
     */
    public function ej5Action()
    {
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();
        $alumnado = $em->createQueryBuilder()
            ->select('a')       // ->select(['a', 'g']) también es equivalente
            ->addSelect('g')
            ->from('AppBundle:Alumno', 'a')
            ->join('a.grupo', 'g')
            ->where('a.fechaNacimiento >= :fechaInicio')
            ->andWhere('a.fechaNacimiento < :fechaFin')
            ->setParameter('fechaInicio', new \DateTime('1997-01-01'))
            ->setParameter('fechaFin', new \DateTime('1998-01-01'))
            ->getQuery()
            ->getResult();

        return $this->render('consultas/alumnado.html.twig', [
            'alumnado' => $alumnado
        ]);
    }

    /**
     * @Route("/ej6", name="ejercicio6")
     */
    public function ej6Action()
    {
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();
        $alumnadoCuenta = $em->createQueryBuilder()
            ->select('COUNT(a)')
            ->from('AppBundle:Alumno', 'a')
            ->where('a.fechaNacimiento >= :fechaInicio')
            ->andWhere('a.fechaNacimiento < :fechaFin')
            ->setParameter('fechaInicio', new \DateTime('1997-01-01'))
            ->setParameter('fechaFin', new \DateTime('1998-01-01'))
            ->getQuery()
            ->getSingleScalarResult();

        return $this->render('consultas/cuenta.html.twig', [
            'numero' => $alumnadoCuenta
        ]);
    }

    /**
     * @Route("/ej7/{anio}", name="ejercicio7", requirements={"anio"="\d{4}"})
     */
    public function ej7Action($anio)
    {
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();
        $alumnado = $em->createQueryBuilder()
            ->select('a')       // ->select(['a', 'g']) también es equivalente
            ->addSelect('g')
            ->from('AppBundle:Alumno', 'a')
            ->join('a.grupo', 'g')
            ->where('a.fechaNacimiento >= :fechaInicio')
            ->andWhere('a.fechaNacimiento < :fechaFin')
            ->setParameter('fechaInicio', new \DateTime("$anio-01-01"))
            ->setParameter('fechaFin', new \DateTime(($anio + 1) . '-01-01'))
            ->orderBy('a.fechaNacimiento', 'DESC')
            ->getQuery()
            ->getResult();

        return $this->render('consultas/alumnado.html.twig', [
            'alumnado' => $alumnado
        ]);
    }

    /**
     * @Route("/ej8", name="ejercicio8")
     */
    public function ej8Action()
    {
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();
        $grupos = $em->createQueryBuilder()
            ->select('g')
            ->addSelect('t')
            ->from('AppBundle:Grupo', 'g')
            ->join('g.tutor', 't')
            ->orderBy('g.descripcion', 'ASC')
            ->getQuery()
            ->getResult();

        return $this->render('consultas/grupos_ej8.html.twig', [
            'grupos' => $grupos
        ]);
    }

    /**
     * @Route("/ej10", name="ejercicio10")
     */
    public function ej10Action()
    {
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();
        $grupos = $em->createQueryBuilder()
            ->select('g')
            ->addSelect('COUNT(a)')
            ->addSelect('t')
            ->from('AppBundle:Grupo', 'g')
            ->innerJoin('g.alumnado', 'a')
            ->innerJoin('g.tutor', 't')
            ->groupBy('g.id')
            ->orderBy('g.descripcion', 'DESC')
            ->getQuery()
            ->getResult();

        return $this->render('consultas/grupos_ej10.html.twig', [
            'grupos' => $grupos
        ]);
    }
}
