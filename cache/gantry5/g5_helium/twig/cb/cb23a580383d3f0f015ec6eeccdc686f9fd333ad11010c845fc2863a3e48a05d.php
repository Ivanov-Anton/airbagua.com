<?php

/* @particles/logo.html.twig */
class __TwigTemplate_ccae7a9badbf94e4f6533fe18a7cdd70521ff6841e9e242660a794f21e85f7c4 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        // line 1
        $this->parent = $this->loadTemplate("@nucleus/partials/particle.html.twig", "@particles/logo.html.twig", 1);
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
        echo "    ";
        $context["url"] = _twig_default_filter($this->env->getExtension('Gantry\Component\Twig\TwigExtension')->urlFunc($this->getAttribute(($context["particle"] ?? null), "url", [])), $this->getAttribute(($context["gantry"] ?? null), "siteUrl", [], "method"));
        // line 5
        echo "    ";
        if ((($context["url"] ?? null) == $this->getAttribute(($context["gantry"] ?? null), "siteUrl", [], "method"))) {
            $context["rel"] = "rel=\"home\"";
        }
        // line 6
        echo "    ";
        $context["class"] = (($this->getAttribute(($context["particle"] ?? null), "class", [])) ? ((("class=\"" . $this->getAttribute(($context["particle"] ?? null), "class", [])) . "\"")) : (""));
        // line 7
        echo "    ";
        $context["image"] = $this->env->getExtension('Gantry\Component\Twig\TwigExtension')->urlFunc($this->getAttribute(($context["particle"] ?? null), "image", []));
        // line 8
        echo "    ";
        $context["height"] = (($this->getAttribute(($context["particle"] ?? null), "height", [])) ? ((("style=\"max-height: " . $this->getAttribute(($context["particle"] ?? null), "height", [])) . "\"")) : (""));
        // line 9
        echo "
    ";
        // line 10
        if (($this->getAttribute(($context["particle"] ?? null), "link", []) == true)) {
            // line 11
            echo "        <a href=\"";
            echo twig_escape_filter($this->env, ($context["url"] ?? null), "html", null, true);
            echo "\" target=\"";
            echo twig_escape_filter($this->env, (($this->getAttribute(($context["particle"] ?? null), "target", [], "any", true, true)) ? (_twig_default_filter($this->getAttribute(($context["particle"] ?? null), "target", []), "_self")) : ("_self")), "html", null, true);
            echo "\" title=\"";
            echo twig_escape_filter($this->env, $this->getAttribute(($context["particle"] ?? null), "text", []), "html", null, true);
            echo "\" ";
            echo (((isset($context["rel"]) || array_key_exists("rel", $context))) ? (_twig_default_filter(($context["rel"] ?? null), "")) : (""));
            echo " ";
            echo (((isset($context["class"]) || array_key_exists("class", $context))) ? (_twig_default_filter(($context["class"] ?? null), "")) : (""));
            echo ">
    ";
        } else {
            // line 12
            echo "<div ";
            echo (((isset($context["class"]) || array_key_exists("class", $context))) ? (_twig_default_filter(($context["class"] ?? null), "")) : (""));
            echo ">";
        }
        // line 13
        echo "        ";
        if ( !twig_test_empty($this->getAttribute(($context["particle"] ?? null), "svg", []))) {
            // line 14
            echo "            ";
            echo $this->getAttribute(($context["particle"] ?? null), "svg", []);
            echo "
        ";
        } elseif (        // line 15
($context["image"] ?? null)) {
            // line 16
            echo "            <img src=\"";
            echo twig_escape_filter($this->env, $this->env->getExtension('Gantry\Component\Twig\TwigExtension')->urlFunc($this->getAttribute(($context["particle"] ?? null), "image", [])), "html", null, true);
            echo "\" ";
            echo (((isset($context["height"]) || array_key_exists("height", $context))) ? (_twig_default_filter(($context["height"] ?? null), "")) : (""));
            echo " alt=\"";
            echo twig_escape_filter($this->env, $this->getAttribute(($context["particle"] ?? null), "text", []), "html", null, true);
            echo "\" />
        ";
        } else {
            // line 18
            echo "            ";
            echo twig_escape_filter($this->env, (($this->getAttribute(($context["particle"] ?? null), "text", [], "any", true, true)) ? (_twig_default_filter($this->getAttribute(($context["particle"] ?? null), "text", []), "Logo")) : ("Logo")), "html", null, true);
            echo "
        ";
        }
        // line 20
        echo "    ";
        if (($this->getAttribute(($context["particle"] ?? null), "link", []) == true)) {
            echo "</a>";
        } else {
            echo "</div>";
        }
    }

    public function getTemplateName()
    {
        return "@particles/logo.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  98 => 20,  92 => 18,  82 => 16,  80 => 15,  75 => 14,  72 => 13,  67 => 12,  53 => 11,  51 => 10,  48 => 9,  45 => 8,  42 => 7,  39 => 6,  34 => 5,  31 => 4,  28 => 3,  11 => 1,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "@particles/logo.html.twig", "/home/dobrod/airbagua.com/www/media/gantry5/engines/nucleus/particles/logo.html.twig");
    }
}
