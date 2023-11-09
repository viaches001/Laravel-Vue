<template>
    <textarea rows="2" :class="classes" :value="value" @input="$emit('input', $event.target.value)" ref="input" v-autogrow></textarea>
</template>

<script>
    // import { TextareaAutogrowDirective  } from 'vue-textarea-autogrow-directive';

    export default {
        props: {
            class_override: {
                type: String,
                default: null,
            },
            
            errors: {
                type: Boolean,
                default: false,
            },
            
            value: {
                type: String,
            },
        },

        directives: {
            autogrow: {
                inserted: function (el) {
                    let observe, minHeight;

                    // If used in a component library such as buefy, a wrapper will be used on the component so check if a child is the textarea
                    el = el.tagName.toLowerCase() === 'textarea' ? el : el.querySelector('textarea')

                    minHeight = parseFloat(getComputedStyle(el).getPropertyValue("min-height"));

                    if (window.attachEvent) {
                    observe = function (element, event, handler) {
                        element.attachEvent("on" + event, handler);
                    };
                    } else {
                    observe = function (element, event, handler) {
                        element.addEventListener(event, handler, false);
                    };
                    }

                    let resize = () => {
                    el.style.height = "auto";
                    let border = el.style.borderTopWidth * 2;
                    el.style.height =
                        (el.scrollHeight < minHeight ? minHeight : el.scrollHeight) +
                        border +
                        "px";
                    };

                    // 0-timeout to get the already changed el
                    let delayedResize = () => {
                    window.setTimeout(resize, 0);
                    };

                    // When the textarea is being shown / hidden
                    var respondToVisibility = (element, callback) => {
                    let intersectionObserver = new IntersectionObserver((entries) => {
                        entries.forEach((entry) => {
                            if (entry.intersectionRatio > 0) callback();
                        });
                        }, {
                        root: document.documentElement
                        }
                    );

                    intersectionObserver.observe(element);
                    };

                    respondToVisibility(el, resize);
                    observe(el, "change", resize);
                    observe(el, "cut", delayedResize);
                    observe(el, "paste", delayedResize);
                    observe(el, "drop", delayedResize);
                    observe(el, "input", resize);

                    resize();
                }
            }
        },

        methods: {
            focus() {
                this.$refs.input.focus()
            }
        },

        computed: {
            classes () {
                if (this.class_override !== null) return this.class_override;
                else return 'form-input rounded-3xl w-full px-6 shadow-sm' + (this.errors ? 'border border-red-400 shadow-outline-red' : '')
            }
        }
    }
</script>

<style scoped>
    textarea {
        overflow: hidden;
        resize: none;
    }

    textarea:disabled {
        cursor: not-allowed;
        background-color: #f8f8f8;
    }
</style>

