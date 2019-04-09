<?php

/* @particles/totop.html.twig */
class __TwigTemplate_e42edb7f4f9a6702def7995ead1c1ab83cc522973258ce32dae5b82190ae2465 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        // line 1
        $this->parent = $this->loadTemplate("@nucleus/partials/particle.html.twig", "@particles/totop.html.twig", 1);
        $this->blocks = [
            'particle' => [$this, 'block_particle'],
        ];
    }

    protected function doGetParent(array $context)
    {
        return "@nucleus/partials/particle.html.twig";
    }

    protected function doDisplay(array $context, array $blocks = [])
    {
        $this->parent->display($context, array_merge($this->blocks, $blocks));
    }

    // line 3
    public function block_particle($context, array $blocks = [])
    {
        // line 4
        echo "<div class=\"";
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute(($context["particle"] ?? null), "css", []), "class", []));
        echo "\">
    <div class=\"g-totop\">
        <a href=\"#\" id=\"g-totop\" rel=\"nofollow\">
            ";
        // line 7
        if ($this->getAttribute(($context["particle"] ?? null), "content", [])) {
            echo $this->getAttribute(($context["particle"] ?? null), "content", []);
        }
        // line 8
        echo "            ";
        if ($this->getAttribute(($context["particle"] ?? null), "icon", [])) {
            echo "<i class=\"";
            echo twig_escape_filter($this->env, $this->getAttribute(($context["particle"] ?? null), "icon", []), "html", null, true);
            echo "\"></i>";
        }
        // line 9
        echo "            ";
        if (( !$this->getAttribute(($context["particle"] ?? null), "icon", []) &&  !$this->getAttribute(($context["particle"] ?? null), "content", []))) {
            echo "To Top";
        }
        // line 10
        echo "        </a>
    </div>
</div>
";
    }

    public function getTemplateName()
    {
        return "@particles/totop.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  54 => 10,  49 => 9,  42 => 8,  38 => 7,  31 => 4,  28 => 3,  11 => 1,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "@particles/totop.html.twig", "/home/dobrod/airbagua.com/www/templates/g5_helium/particles/totop.html.twig");
    }
}
