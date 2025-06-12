document.addEventListener('DOMContentLoaded', function () {
	if (typeof ddeWeightData === 'undefined') return;

	const ctx = document.getElementById('dogWeightChart');
	const { labels, data, goal } = ddeWeightData;

	// Detect if goal is reached
	const lastWeight = data[data.length - 1];
	const goalReached = lastWeight <= goal;

	// Trigger confetti if goal hit and not already triggered
	if (goalReached && !localStorage.getItem('dde_goal_confetti')) {
		localStorage.setItem('dde_goal_confetti', '1');

		const script = document.createElement('script');
		script.src = 'https://cdn.jsdelivr.net/npm/canvas-confetti@1.6.0/dist/confetti.browser.min.js';
		script.onload = function () {
			confetti({
				particleCount: 150,
				spread: 90,
				origin: { y: 0.6 }
			});
		};
		document.head.appendChild(script);
	}

	const chart = new Chart(ctx, {
		type: 'line',
		data: {
			labels: labels,
			datasets: [
				{
					label: 'Weight (kg)',
					data: data,
					borderColor: 'blue',
					tension: 0.3,
					fill: false
				},
				{
					label: 'Target',
					data: labels.map(() => goal),
					borderColor: 'green',
					borderDash: [5, 5],
					pointRadius: 0,
					fill: false
				}
			]
		},
		options: {
			scales: {
				y: {
					beginAtZero: false
				}
			},
			plugins: {
				legend: {
					position: 'top'
				}
			}
		}
	});
});