import {
  Home, LayoutDashboard, Briefcase, FileText, Wallet, BarChart3,
  Users, ShieldCheck, Settings, LogOut, Bell, User, Download,
  FolderOpen, TrendingUp, ClipboardCheck, BookOpen, HelpCircle,
  ChevronRight, Eye, CheckCircle2, Clock, AlertCircle,
} from 'lucide-react';

type IconComponent = typeof Home;

const iconMap: Record<string, IconComponent> = {
  home: Home,
  dashboard: LayoutDashboard,
  projects: Briefcase,
  briefcase: Briefcase,
  documents: FileText,
  wallet: Wallet,
  reports: BarChart3,
  users: Users,
  verification: ShieldCheck,
  settings: Settings,
  logout: LogOut,
  bell: Bell,
  user: User,
  download: Download,
  folder: FolderOpen,
  trending: TrendingUp,
  clipboard: ClipboardCheck,
  book: BookOpen,
  help: HelpCircle,
  chevronRight: ChevronRight,
  eye: Eye,
  check: CheckCircle2,
  clock: Clock,
  alert: AlertCircle,
};

export function getIcon(name: string): IconComponent {
  return iconMap[name] ?? Home;
}
