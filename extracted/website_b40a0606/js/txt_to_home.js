function loadMultipleTexts(fileMapping) {
  for (const [elementId, filePath] of Object.entries(fileMapping)) {
    fetch(filePath)
      .then(response => {
        if (!response.ok) {
          throw new Error(`Failed to load ${filePath}: ${response.statusText}`);
        }
        return response.text();
      })
      .then(data => {
        const lines = data.split('\n');
        const formattedLines = lines.map(line => {
          const specialIndentMatch = line.match(/^@@@\s*(.*)/);
          if (specialIndentMatch) {
            const combinedLine = specialIndentMatch[1];
            return `<span class="numbered-line">${combinedLine}</span>`;
          }
          if (/^\d+\.\s/.test(line)) {
            return `<span class="numbered-line">${line}</span>`;
          }
          return `<span class="normal-line">${line}</span>`;
        });

        const targetElement = document.getElementById(elementId);
        if (targetElement) {
          targetElement.innerHTML = formattedLines.join('');
        } else {
          console.error(`Element with ID "${elementId}" not found.`);
        }
      })
      .catch(error => console.error(error));
  }
}

const fileMapping = {
  'intro-content': '../info/intro.txt?v=<?php echo filemtime("../info/intro.txt"); ?>',
  'resume-content': '../info/resume.txt?v=<?php echo filemtime("../info/resume.txt"); ?>'
};

loadMultipleTexts(fileMapping);
