<?php

/* @nucleus/layout/section.html.twig */
class __TwigTemplate_0cc155d5f28eb44fb1e608fe2ee3582687c725dd24343ee57598f63b2ef5290f extends Twig_Template
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
        $context["tag_type"] = (($this->getAttribute(($context["segment"] ?? null), "subtype", [], "any", true, true)) ? (_twig_default_filter($this->getAttribute(($context["segment"] ?? null), "subtype", []), "section")) : ("section"));
        // line 2
        $context["attr_id"] = (($this->getAttribute($this->getAttribute(($context["segment"] ?? null), "attributes", []), "id", [])) ? ($this->getAttribute($this->getAttribute(($context["segment"] ?? null), "attributes", []), "id", [])) : (("g-" . $this->getAttribute(($context["segment"] ?? null), "id", []))));
        // line 3
        $context["attr_class"] = ($this->getAttribute($this->getAttribute(($context["segment"] ?? null), "attributes", []), "class", []) . (($this->getAttribute($this->getAttribute(        // line 4
($context["segment"] ?? null), "attributes", []), "variations", [])) ? ((" " . twig_join_filter($this->getAttribute($this->getAttribute(($context["segment"] ?? null), "attributes", []), "variations", []), " "))) : ("")));
        // line 5
        $context["attr_extra"] = $this->env->getExtension('Gantry\Component\Twig\TwigExtension')->attributeArrayFilter($this->getAttribute($this->getAttribute(($context["segment"] ?? null), "attributes", []), "extra", []));
        // line 6
        $context["boxed"] = $this->getAttribute($this->getAttribute(($context["segment"] ?? null), "attributes", []), "boxed", []);
        // line 7
        if ( !(null === ($context["boxed"] ?? null))) {
            // line 8
            echo "    ";
            $context["boxed"] = (((twig_trim_filter(($context["boxed"] ?? null)) == "")) ? ($this->getAttribute($this->getAttribute($this->getAttribute($this->getAttribute($this->getAttribute(($context["gantry"] ?? null), "config", []), "page", []), "body", []), "layout", []), "sections", [])) : (($context["boxed"] ?? null)));
        }
        // line 11
        ob_start();
        // line 12
        echo "    ";
        if ($this->getAttribute(($context["segment"] ?? null), "children", [])) {
            // line 13
            echo "        ";
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable(($context["segments"] ?? null));
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
            foreach ($context['_seq'] as $context["_key"] => $context["segment"]) {
                // line 14
                echo "            ";
                $this->loadTemplate((("@nucleus/layout/" . $this->getAttribute($context["segment"], "type", [])) . ".html.twig"), "@nucleus/layout/section.html.twig", 14)->display(array_merge($context, ["segments" => $this->getAttribute($context["segment"], "children", [])]));
                // line 15
                echo "        ";
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
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['segment'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 16
            echo "    ";
        }
        $context["html"] = ('' === $tmp = ob_get_clean()) ? '' : new Twig_Markup($tmp, $this->env->getCharset());
        // line 19
        if (($this->getAttribute($this->getAttribute(($context["segment"] ?? null), "attributes", []), "sticky", []) || twig_trim_filter(($context["html"] ?? null)))) {
            // line 20
            if (( !(null === ($context["boxed"] ?? null)) && ((($context["boxed"] ?? null) == 0) || (($context["boxed"] ?? null) == 2)))) {
                // line 21
                echo "        ";
                ob_start();
                // line 22
                echo "        <div class=\"g-container\">";
                echo ($context["html"] ?? null);
                echo "</div>
        ";
                $context["html"] = ('' === $tmp = ob_get_clean()) ? '' : new Twig_Markup($tmp, $this->env->getCharset());
                // line 24
                echo "    ";
            }
            // line 25
            echo "
    ";
            // line 26
            ob_start();
            // line 27
            echo "    ";
            if ((($context["boxed"] ?? null) == 2)) {
                $context["attr_class"] = (($context["attr_class"] ?? null) . " g-flushed");
            }
            // line 28
            echo "    ";
            $context["attr_class"] = ((($context["attr_class"] ?? null)) ? (((" class=\"" . twig_trim_filter(($context["attr_class"] ?? null))) . "\"")) : (""));
            // line 29
            echo "<";
            echo twig_escape_filter($this->env, ($context["tag_type"] ?? null), "html", null, true);
            echo " id=\"";
            echo twig_escape_filter($this->env, ($context["attr_id"] ?? null), "html", null, true);
            echo "\"";
            echo ($context["attr_class"] ?? null);
            echo ($context["attr_extra"] ?? null);
            echo ">
        ";
            // line 30
            echo ($context["html"] ?? null);
            echo "
    </";
            // line 31
            echo twig_escape_filter($this->env, ($context["tag_type"] ?? null), "html", null, true);
            echo ">";
            $context["html"] = ('' === $tmp = ob_get_clean()) ? '' : new Twig_Markup($tmp, $this->env->getCharset());
            // line 34
            if ((($context["boxed"] ?? null) == 1)) {
                // line 35
                echo "    <div class=\"g-container\">";
                echo ($context["html"] ?? null);
                echo "</div>
    ";
            } else {
                // line 37
                echo "    ";
                echo ($context["html"] ?? null);
                echo "
    ";
            }
        }
    }

    public function getTemplateName()
    {
        return "@nucleus/layout/section.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  135 => 37,  129 => 35,  127 => 34,  123 => 31,  119 => 30,  109 => 29,  106 => 28,  101 => 27,  99 => 26,  96 => 25,  93 => 24,  87 => 22,  84 => 21,  82 => 20,  80 => 19,  76 => 16,  62 => 15,  59 => 14,  41 => 13,  38 => 12,  36 => 11,  32 => 8,  30 => 7,  28 => 6,  26 => 5,  24 => 4,  23 => 3,  21 => 2,  19 => 1,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "@nucleus/layout/section.html.twig", "/home/dobrod/airbagua.com/www/media/gantry5/engines/nucleus/templates/layout/section.html.twig");
    }
}
