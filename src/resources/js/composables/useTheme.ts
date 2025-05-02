import { inject } from 'vue'

export function useTheme() {
    const theme = inject('theme') as { isDarkMode: boolean; toggleTheme: () => void } | undefined
    if (!theme) {
        throw new Error('useTheme must be used within a ThemeProvider')
    }
    return theme
}
