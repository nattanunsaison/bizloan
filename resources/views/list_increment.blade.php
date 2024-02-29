<html>
    <head>
<style>
ol {
  counter-reset: count;
}
li {
  counter-increment: count;
}
li::marker {
  content:
  counters(count, ".", decimal) ". "  ;
}
/* li::before {
  content:
    counters(count, "~", upper-alpha) " == "
    counters(count,  "*", lower-alpha);
} */
</style>
    </head>
    <body>
    <ol>
        <li>
            <ol>
            <li></li>
            <li></li>
            <li></li>
            </ol>
        </li>
        <li></li>
        <li></li>
        <li>
            <ol>
            <li></li>
            <li>
                <ol>
                <li></li>
                <li></li>
                <li></li>
                </ol>
            </li>
            </ol>
        </li>
    </ol>
    </body>
</html>