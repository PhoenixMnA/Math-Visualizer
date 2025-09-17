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
    <!-- Results Panel -->
    <div id="resultsBox" class="p-4 border rounded bg-gray-100 dark:bg-gray-800 dark:border-gray-700">
      <h2 class="text-lg font-semibold mb-2">📝 Results</h2>
      <ul id="resultsList" class="list-disc pl-5 space-y-1"></ul>
    </div>
  </div>

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

    

  <script>
   // --- START: Corrected JavaScript Block ---

  let darkMode = true;
  const xValues = [];
  for (let i = -10; i <= 10; i += 0.5) xValues.push(i); // Increased resolution

  // Process calculus/algebra and return a plottable expression
  function processEquation(eq) {
    try {
      // Derivative (using math.js)
      if (eq.startsWith("derivative(")) {
        const parsed = /derivative\((.*),\s*(\w)\)/.exec(eq);
        if (parsed) {
          const expr = parsed[1];
          const variable = parsed[2];
          const derivative = math.derivative(expr, variable).toString();
          addResult(`Derivative of ${expr} wrt ${variable} = ${derivative}`);
          return derivative;
        }
      }

      // Integral (FIXED: using nerdamer.js)
      if (eq.startsWith("integral(")) {
        const parsed = /integral\((.*),\s*(\w)\)/.exec(eq);
        if (parsed) {
          const expr = parsed[1];
          const variable = parsed[2];
          // Use nerdamer for symbolic integration
          const integral = nerdamer.integrate(expr, variable).toString();
          addResult(`Integral of ${expr} wrt ${variable} = ${integral} + C`);
          return integral; // Return without "+ C" for plotting
        }
      }

      // Simplify (using math.js)
      if (eq.startsWith("simplify(")) {
        const parsed = /simplify\((.*)\)/.exec(eq);
        if (parsed) {
          const simplified = math.simplify(parsed[1]).toString();
          addResult(`Simplify ${parsed[1]} = ${simplified}`);
          return simplified;
        }
      }

      return eq; // default (no processing)
    } catch (err) {
      console.error("Process error:", err);
      addResult(`⚠️ Error processing "${eq}": ${err.message}`);
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
        const is3D = /(^|[^a-zA-Z])y([^a-zA-Z]|$)/.test(processed);

        // FIXED: Use if/else for plotting
        if (is3D) {
          // 3D Plot (only for expressions with 'y')
          const yValues3D = [...xValues];
          const zValues = [];
          for (let xi of xValues) {
            const row = [];
            for (let yi of yValues3D) {
              let z;
              try {
                z = math.evaluate(processed, { x: xi, y: yi });
              } catch { z = null; }
              row.push(Number.isFinite(z) ? z : null);
            }
            zValues.push(row);
          }
          datasets3D.push({
            z: zValues, x: xValues, y: yValues3D,
            type: "surface", name: eq
          });
        } else {
          // 2D Plot (for expressions with only 'x')
          const yValues = xValues.map(x => {
            const val = math.evaluate(processed, { x });
            return Number.isFinite(val) ? val : null;
          });
          datasets2D.push({
            x: xValues, y: yValues,
            mode: "lines", name: eq
          });
        }
      } catch (err) {
        console.error("Plot error:", err);
        addResult(`⚠️ Error plotting "${eq}": ${err.message}`);
      }
    });

    const layout = {
      paper_bgcolor: darkMode ? "#1f2937" : "#ffffff",
      plot_bgcolor: darkMode ? "#1f2937" : "#ffffff",
      font: { color: darkMode ? "#f3f4f6" : "#111827" }
    };

    Plotly.newPlot("plot2D", datasets2D, { ...layout, title: "2D Plot" });
    Plotly.newPlot("plot3D", datasets3D, { ...layout, title: "3D Plot" });
  }

  // Add to results panel
  function addResult(text) {
    const li = document.createElement("li");
    li.textContent = text;
    document.getElementById("resultsList").appendChild(li);
  }

  // --- Event Listeners ---
  document.addEventListener("DOMContentLoaded", () => {
    // Initial plot on page load
    const initialEqs = document.getElementById("equation").value.split(",").map(e => e.trim()).filter(e => e);
    plotEquations(initialEqs);

    // Submit form
   // Submit form
document.getElementById("equationForm").addEventListener("submit", e => {
  e.preventDefault();
  const eqInput = document.getElementById("equation").value;
  
  // ✅ FIXED: This regex splits by commas but ignores commas inside parentheses.
  const eqs = eqInput.split(/,\s*(?![^()]*\))/).map(e => e.trim()).filter(e => e);
  
  plotEquations(eqs);
});

    // Dark mode toggle
    document.getElementById("darkToggle").addEventListener("click", () => {
      darkMode = !darkMode;
      document.body.classList.toggle("dark", darkMode);
      document.getElementById("darkToggle").textContent = darkMode ? "☀️ Light" : "🌙 Dark";
      const eqs = document.getElementById("equation").value.split(",").map(e => e.trim()).filter(e => e);
      plotEquations(eqs); // Redraw plots with new theme
    });

    // Reset
    document.getElementById("resetBtn").addEventListener("click", () => {
      const defaultEqs = "x^2, sin(x), x^2 + y^2";
      document.getElementById("equation").value = defaultEqs;
      plotEquations(defaultEqs.split(",").map(e => e.trim()));
    });

    // FIXED: The 'examples' element listener is removed as the element doesn't exist.
    // If you add an "examples" dropdown in your HTML, you can restore this.
  });

  // --- END: Corrected JavaScript Block ---
  </script>
</body>
</html>

