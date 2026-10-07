import type { ReactNode } from 'react';
import WorkspaceLayout from '@/layouts/workspace-layout';
import { adminNav } from '@/config/navigation';

export default function AdminLayout({ children }: { children: ReactNode }) {
  return <WorkspaceLayout config={adminNav}>{children}</WorkspaceLayout>;
}
