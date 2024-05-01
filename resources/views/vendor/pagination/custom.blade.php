<!--@if ($paginator->hasPages())-->
<!--    <ul class="flex justify-center list-none p-0">-->
<!--        @if ($paginator->onFirstPage())-->
<!--            <li class="mr-2"><span>Prev</span></li>-->
<!--        @else-->
<!--            <li class="mr-2"><a href="{{ $paginator->previousPageUrl() }}" rel="prev">Prev</a></li>-->
<!--        @endif-->

<!--        @foreach ($elements as $element)-->
<!--            @if (is_string($element))-->
<!--                <li class="mr-2"><span>{{ $element }}</span></li>-->
<!--            @endif-->

<!--            @if (is_array($element))-->
<!--                @foreach ($element as $page => $url)-->
<!--                    @if ($page == $paginator->currentPage())-->
<!--                        <li class="mr-2">-->
<!--                            <span>-->
<!--                                <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACAAAAAgCAYAAABzenr0AAAACXBIWXMAAAsTAAALEwEAmpwYAAABlElEQVR4nO2VwStEURTGf0YhZUmNwUKylb9Bmo3ZsJQVyVIp/AWW/g5MFsKChQmF/SwmKTZiY2SGRCZPt75Xt9d77840IXpfnd6cc777vq97z30DCRLUh37AA574BfQB+zKQ/ynBPFCVqB9lYMixdh3oiOl3iBMrXg4IV2TIJY74RWBEebsC1YriRCIvwi6QCemngAlgB7gC3oB75b4BT/VzoKY4U83vR6IqQpi4qV0EdscO28Cnnq8KuxZroCKCOQobA8CdejfAvDht6i0EDGSBce1YSr+z9RjYFmHPMtEKHKt+AHTFrHcJOA0MAw8RW3ztEDf4ENcfvOAN8MSJRQbYtI7DD7PtLlyKOxnSm1KvRIO4jZiLMCyJ+wjMAt1ADzCnmuktNmqgpoVmFmzkgALwojgCBoGtmJuyEfIeJ8IGZy1CoKQ5mgFOLXMnwDTQ4pZzG8gpfweWgV7Fimrm3o9Z/E5glCbgBQwUlBvBIFbVO4xZ37SBZ+XpEG7a+uP6NgNegx+bxICXHAHJEPLHr2GC/40vo+PaWvFjizgAAAAASUVORK5CYII=">-->
<!--                            </span>-->
<!--                        </li>-->
<!--                    @else-->
<!--                        <li class="mr-2"><a href="{{ $url }}">{{ $page }}</a></li>-->
<!--                    @endif-->
<!--                @endforeach-->
<!--            @endif-->
<!--        @endforeach-->

<!--        @if ($paginator->hasMorePages())-->
<!--            <li class="mr-2"><a href="{{ $paginator->nextPageUrl() }}" rel="next">Next</a></li>-->
<!--        @else-->
<!--            <li class="mr-2"><span>Next</span></li>-->
<!--        @endif-->
<!--    </ul>-->
<!--@endif-->