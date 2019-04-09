<?php

/* @nucleus/layout/offcanvas.html.twig */
class __TwigTemplate_be1db91c8de0ec2f29add310878acd8f9d5a57a53197c2cf12da8dcd96f97839 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = [
        ];
    }

    protected function doDisplay(array $context, array $blocks = [])
    {
        // line 1
        $context["attr_class"] = (($this->getAttribute($this->getAttribute(($context["segment"] ?? null), "attributes", []), "class", [])) ? (((" class=\"" . twig_escape_filter($this->env, $this->getAttribute($this->getAttribute(($context["segment"] ?? null), "attributes", []), "class", []))) . "\"")) : (""));
        // line 2
        $context["attr_extra"] = $this->env->getExtension('Gantry\Component\Twig\TwigExtension')->attributeArrayFilter($this->getAttribute($this->getAttribute(($context["segment"] ?? null), "attributes", []), "extra", []));
        // line 3
        echo "
";
        // line 4
        ob_start();
        // line 5
        echo "    ";
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable($this->getAttribute(($context["segment"] ?? null), "children", []));
        $context['loop'] = [
          'parent' => $context['_parent'],
          'index0' => 0,
          'index'  => 1,
          'first'  => true,
        ];
        if (is_array($context['_seq']) || (is_object($context['_seq']) && $context['_seq'] instanceof Countable)) {
            $length = count($context['_seq']);
            $context['loop']['revindex0'] = $length - 1;
            $context['loop']['revindex'] = $length;
            $context['loop']['length'] = $length;
            $context['loop']['last'] = 1 === $length;
        }
        foreach ($context['_seq'] as $context["_key"] => $context["child"]) {
            // line 6
            echo "        ";
            $this->loadTemplate((("@nucleus/layout/" . $this->getAttribute($context["child"], "type", [])) . ".html.twig"), "@nucleus/layout/offcanvas.html.twig", 6)->display(array_merge($context, ["segments" => $this->getAttribute($context["child"], "children", [])]));
            // line 7
            echo "    ";
            ++$context['loop']['index0'];
            ++$context['loop']['index'];
            $context['loop']['first'] = false;
            if (isset($context['loop']['length'])) {
                --$context['loop']['revindex0'];
                --$context['loop']['revindex'];
                $context['loop']['last'] = 0 === $context['loop']['revindex0'];
            }
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['child'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        $context["offcanvas"] = ('' === $tmp = ob_get_clean()) ? '' : new Twig_Markup($tmp, $this->env->getCharset());
        // line 10
        if (($this->getAttribute($this->getAttribute(($context["segment"] ?? null), "attributes", []), "sticky", []) || twig_trim_filter(($context["offcanvas"] ?? null)))) {
            // line 11
            echo "<div id=\"g-offcanvas\" ";
            echo ($context["attr_class"] ?? null);
            echo ($context["attr_extra"] ?? null);
            echo " data-g-offcanvas-swipe=\"";
            echo twig_escape_filter($this->env, (($this->getAttribute($this->getAttribute(($context["segment"] ?? null), "attributes", [], "any", false, true), "swipe", [], "any", true, true)) ? (_twig_default_filter($this->getAttribute($this->getAttribute(($context["segment"] ?? null), "attributes", [], "any", false, true), "swipe", []), "1")) : ("1")), "html", null, true);
            echo "\" data-g-offcanvas-css3=\"";
            echo twig_escape_filter($this->env, (($this->getAttribute($this->getAttribute(($context["segment"] ?? null), "attributes", [], "any", false, true), "css3animation", [], "any", true, true)) ? (_twig_default_filter($this->getAttribute($this->getAttribute(($context["segment"] ?? null), "attributes", [], "any", false, true), "css3animation", []), "1")) : ("1")), "html", null, true);
            echo "\">
    ";
            // line 12
            echo ($context["offcanvas"] ?? null);
            // line 13
            echo "</div>
";
        }
    }

    public function getTemplateName()
    {
        return "@nucleus/layout/offcanvas.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  78 => 13,  76 => 12,  66 => 11,  64 => 10,  49 => 7,  46 => 6,  28 => 5,  26 => 4,  23 => 3,  21 => 2,  19 => 1,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "@nucleus/layout/offcanvas.html.twig", "/home/dobrod/airbagua.com/www/media/gantry5/engines/nucleus/templates/layout/offcanvas.html.twig");
    }
}
