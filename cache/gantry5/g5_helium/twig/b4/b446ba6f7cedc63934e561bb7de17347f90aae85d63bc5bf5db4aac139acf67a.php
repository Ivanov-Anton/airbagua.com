<?php

/* partials/page_head.html.twig */
class __TwigTemplate_4556def9c4cf401531d1e96e5c482ce0cd3d14d94a9521394573cd72124f9088 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        // line 1
        $this->parent = $this->loadTemplate("@nucleus/page_head.html.twig", "partials/page_head.html.twig", 1);
        $this->blocks = [
            'head_title' => [$this, 'block_head_title'],
            'head_application' => [$this, 'block_head_application'],
            'head_platform' => [$this, 'block_head_platform'],
            'head' => [$this, 'block_head'],
        ];
    }

    protected function doGetParent(array $context)
    {
        return "@nucleus/page_head.html.twig";
    }

    protected function doDisplay(array $context, array $blocks = [])
    {
        $this->parent->display($context, array_merge($this->blocks, $blocks));
    }

    // line 3
    public function block_head_title($context, array $blocks = [])
    {
    }

    // line 6
    public function block_head_application($context, array $blocks = [])
    {
        // line 7
        echo "<jdoc:include type=\"head\" />";
    }

    // line 10
    public function block_head_platform($context, array $blocks = [])
    {
        // line 11
        $this->displayParentBlock("head_platform", $context, $blocks);
        echo "
    ";
        // line 12
        if ($this->getAttribute($this->getAttribute(($context["gantry"] ?? null), "theme", []), "joomla", [])) {
            // line 13
            echo "        <link rel=\"stylesheet\" href=\"";
            echo twig_escape_filter($this->env, $this->env->getExtension('Gantry\Component\Twig\TwigExtension')->urlFunc("gantry-assets://css/bootstrap-gantry.css"), "html", null, true);
            echo "\" type=\"text/css\" />
        <link rel=\"stylesheet\" href=\"";
            // line 14
            echo twig_escape_filter($this->env, $this->env->getExtension('Gantry\Component\Twig\TwigExtension')->urlFunc("gantry-engine://css-compiled/joomla.css"), "html", null, true);
            echo "\" type=\"text/css\" />
        ";
            // line 15
            if (($this->getAttribute($this->getAttribute(($context["gantry"] ?? null), "page", []), "direction", []) == "rtl")) {
                // line 16
                echo "        <link rel=\"stylesheet\" href=\"";
                echo twig_escape_filter($this->env, $this->env->getExtension('Gantry\Component\Twig\TwigExtension')->urlFunc("media/jui/css/bootstrap-rtl.css"), "html", null, true);
                echo "\" type=\"text/css\" />
        ";
            }
            // line 18
            echo "        <link rel=\"stylesheet\" href=\"";
            echo twig_escape_filter($this->env, $this->env->getExtension('Gantry\Component\Twig\TwigExtension')->urlFunc("media/jui/css/icomoon.css"), "html", null, true);
            echo "\" type=\"text/css\" />
    ";
        }
        // line 20
        echo "    ";
        if ($this->getAttribute(($context["gantry"] ?? null), "debug", [])) {
            // line 21
            echo "        <link rel=\"stylesheet\" href=\"";
            echo twig_escape_filter($this->env, $this->env->getExtension('Gantry\Component\Twig\TwigExtension')->urlFunc("media/cms/css/debug.css"), "html", null, true);
            echo "\" type=\"text/css\" />
    ";
        }
    }

    // line 25
    public function block_head($context, array $blocks = [])
    {
        // line 26
        $this->displayParentBlock("head", $context, $blocks);
    }

    public function getTemplateName()
    {
        return "partials/page_head.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  89 => 26,  86 => 25,  78 => 21,  75 => 20,  69 => 18,  63 => 16,  61 => 15,  57 => 14,  52 => 13,  50 => 12,  46 => 11,  43 => 10,  39 => 7,  36 => 6,  31 => 3,  11 => 1,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "partials/page_head.html.twig", "/home/dobrod/airbagua.com/www/media/gantry5/engines/nucleus/twig/partials/page_head.html.twig");
    }
}
