const lucide = require('/Users/wmafendi/Herd/donation-ui-prototype/node_modules/lucide/dist/umd/lucide.js');

function toSvg(name, data, classDefault = 'size-5') {
    let inner = data.map(([tag, attrs]) => {
        const attrStr = Object.entries(attrs)
            .map(([k, v]) => `${k}="${v}"`)
            .join(' ');
        return `        <${tag} ${attrStr}/>`;
    }).join('\n');

    return `    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="{{ \$class ?? '${classDefault}' }}">\n${inner}\n    </svg>`;
}

const icons = {
    'dollar-sign': lucide.DollarSign,
    'percent': lucide.Percent,
    'refresh-cw': lucide.RefreshCw,
    'user': lucide.User,
    'heart': lucide.Heart,
    'message-square': lucide.MessageSquare,
    'external-link': lucide.ExternalLink,
    'zap': lucide.Zap,
    'hash': lucide.Hash,
    'settings': lucide.Settings,
    'mail': lucide.Mail,
    'users': lucide.Users,
    'log-out': lucide.LogOut,
    'menu': lucide.Menu,
    'chevron-down': lucide.ChevronDown,
    'search': lucide.Search,
    'inbox': lucide.Inbox,
    'download': lucide.Download,
    'corner-up-left': lucide.CornerUpLeft,
    'copy': lucide.Copy,
    'check': lucide.Check,
    'banknote': lucide.Banknote,
    'pencil': lucide.Pencil,
    'trash-2': lucide.Trash2,
    'circle-check': lucide.CircleCheck,
    'circle-x': lucide.CircleX,
    'triangle-alert': lucide.TriangleAlert,
    'circle-alert': lucide.CircleAlert,
    'info': lucide.Info,
    'activity': lucide.Activity,
    'layout-dashboard': lucide.LayoutDashboard,
    'target': lucide.Target,
};

let output = `@props(['name', 'class' => null])

@switch($name)
`;

for (const [name, data] of Object.entries(icons)) {
    if (!data) {
        console.log('MISSING: ' + name);
        continue;
    }
    console.log('OK: ' + name);
    output += `    @case('${name}')\n${toSvg(name, data, '{{ $class ?? \'size-5\' }}')}\n        @break\n\n`;
}

output += `    @default
        <span class="text-red-500">{{ $name }}</span>
@endswitch
`;

const fs = require('fs');
fs.mkdirSync('./resources/views/components', { recursive: true });
fs.writeFileSync('./resources/views/components/icon.blade.php', output);
console.log('\nGenerated: resources/views/components/icon.blade.php');
