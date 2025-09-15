 <!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Math Visualizer</title>
<!-- ✅ Plotly (latest v3 release) -->
<script src="https://cdn.plot.ly/plotly-3.1.0.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/mathjs/14.7.0/math.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/nerdamer@latest/nerdamer.core.js"></script>
<script src="https://cdn.jsdelivr.net/npm/nerdamer@latest/Calculus.js"></script>

<script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 text-gray-900 dark:bg-gray-900 dark:text-gray-100 transition">

  <div class="container mx-auto p-6 space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
      <h1 class="text-3xl font-bold">🌀 Math Visualizer</h1>
      <div class="flex gap-3">
        <button id="darkToggle" class="px-4 py-2 rounded bg-gray-700 text-white">🌙 Dark</button>
        <button id="resetBtn" class="px-4 py-2 rounded bg-red-600 text-white">♻ Reset</button>
      </div>
    </div>

    <!-- Input Form -->
    <form id="equationForm" class="space-y-4">
      <label class="block text-lg font-semibold">Enter equations (comma separated):</label>
      <input type="text" id="equation" name="equation"
             value="x^2, sin(x), x^2 + y^2"
             class="w-full px-4 py-2 rounded border dark:bg-gray-800 dark:border-gray-700">

    

      <button type="submit" class="px-4 py-2 rounded bg-green-600 text-white">📈 Plot</button>
    </form>

    <!-- Graphs -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
      <div>
        <h2 class="text-lg font-semibold mb-2">📉 2D Plot</h2>
        <div id="plot2D" class="w-full h-[500px] border rounded bg-white dark:bg-gray-800"></div>
      </div>
      <div>
        <h2 class="text-lg font-semibold mb-2">🌐 3D Plot</h2>
        <div id="plot3D" class="w-full h-[500px] border rounded bg-white dark:bg-gray-800"></div>
      </div>
    </div>

    <!-- Results Panel -->
    <div id="resultsBox" class="p-4 border rounded bg-gray-100 dark:bg-gray-800 dark:border-gray-700">
      <h2 class="text-lg font-semibold mb-2">📝 Results</h2>
      <ul id="resultsList" class="list-disc pl-5 space-y-1"></ul>
    </div>
  </div>

  <script>
    let darkMode = false;
    const xValues = [];
    for (let i = -10; i <= 10; i++) xValues.push(i);

    // Process calculus/algebra
   // Process calculus/algebra and return a plottable expression
function processEquation(eq) {
  try {
    // Derivative
    if (eq.startsWith("derivative(")) {
      const parsed = /derivative\((.*),\s*(\w)\)/.exec(eq);
      if (parsed) {
        const expr = parsed[1];
        const variable = parsed[2];
        const derivative = math.derivative(expr, variable).toString();
        addResult(`Derivative of ${expr} wrt ${variable} = ${derivative}`);
        return derivative; // now plottable
      }
    }

    // Integral
    if (eq.startsWith("integral(")) {
      const parsed = /integral\((.*),\s*(\w)\)/.exec(eq);
      if (parsed) {
        const expr = parsed[1];
        const variable = parsed[2];
        const integral = math.integral(expr, variable).toString();
        addResult(`Integral of ${expr} wrt ${variable} = ${integral}`);
        return integral; // plottable
      }
    }

    // Simplify
    if (eq.startsWith("simplify(")) {
      const parsed = /simplify\((.*)\)/.exec(eq);
      if (parsed) {
        const simplified = math.simplify(parsed[1]).toString();
        addResult(`Simplify ${parsed[1]} = ${simplified}`);
        return simplified; // plottable
      }
    }

    return eq; // default (no processing)
  } catch (err) {
    console.error("Process error:", err);
    addResult(`⚠️ Error processing "${eq}"`);
    return null;
  }
}


    function plotEquations(equations) {
  const datasets2D = [];
  const datasets3D = [];
  document.getElementById("resultsList").innerHTML = ""; // clear results

  equations.forEach(eq => {
    const processed = processEquation(eq);
    if (!processed) return;

    try {
      // Decide if equation has 'y' in it
      const hasY = /(^|[^a-zA-Z])y([^a-zA-Z]|$)/.test(processed);

      if (!hasY) {
        // 2D Plot (only in terms of x)
        const yValues = xValues.map(x => {
          const val = math.evaluate(processed, { x });
          return Number.isFinite(val) ? val : null;
        });
        datasets2D.push({
          x: xValues,
          y: yValues,
          mode: "lines",
          name: eq
        });
      }

      // 3D Plot (for any expression)
      const yValues3D = [...xValues];
      const zValues = [];
      for (let xi of xValues) {
        const row = [];
        for (let yi of yValues3D) {
          let z;
          try {
            z = math.evaluate(processed, { x: xi, y: yi });
          } catch {
            z = null;
          }
          row.push(Number.isFinite(z) ? z : null);
        }
        zValues.push(row);
      }
      datasets3D.push({
        z: zValues,
        x: xValues,
        y: yValues3D,
        type: "surface",
        opacity: 0.8,
        name: eq
      });

    } catch (err) {
      console.error("Plot error:", err);
      addResult(`⚠️ Error plotting "${eq}"`);
    }
  });

  // 2D
  Plotly.newPlot("plot2D", datasets2D, {
    title: "2D Plot",
    paper_bgcolor: darkMode ? "#111827" : "#ffffff",
    plot_bgcolor: darkMode ? "#111827" : "#ffffff",
    font: { color: darkMode ? "#f3f4f6" : "#111827" }
  });

  // 3D
  Plotly.newPlot("plot3D", datasets3D, {
    title: "3D Plot",
    paper_bgcolor: darkMode ? "#111827" : "#ffffff",
    plot_bgcolor: darkMode ? "#111827" : "#ffffff",
    font: { color: darkMode ? "#f3f4f6" : "#111827" }
  });
}

    // Add to results panel
    function addResult(text) {
      const li = document.createElement("li");
      li.textContent = text;
      document.getElementById("resultsList").appendChild(li);
    }

    // Default
    plotEquations(["x^2"]);

    // Submit form
    document.getElementById("equationForm").addEventListener("submit", e => {
      e.preventDefault();
      const eqInput = document.getElementById("equation").value;
      const eqs = eqInput.split(",").map(e => e.trim()).filter(e => e);
      plotEquations(eqs);
    });

    // Examples dropdown
    document.getElementById("examples").addEventListener("change", function () {
      if (this.value) {
        document.getElementById("equation").value += (document.getElementById("equation").value ? ", " : "") + this.value;
      }
    });

    // Dark mode toggle
    document.getElementById("darkToggle").addEventListener("click", () => {
      darkMode = !darkMode;
      document.body.classList.toggle("dark");
      const eqs = document.getElementById("equation").value.split(",").map(e => e.trim()).filter(e => e);
      plotEquations(eqs);
    });

    // Reset
    document.getElementById("resetBtn").addEventListener("click", () => {
      document.getElementById("equation").value = "x^2";
      plotEquations(["x^2"]);
    });
  </script>
</body>
</html>

